<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Check if viewing a specific album
        if ($request->has('album')) {
            $albumName = $request->query('album');
            $query = Gallery::query();

            if ($albumName === 'Umum') {
                $query->where(function ($q) {
                    $q->whereNull('category_label')
                      ->orWhere('category_label', '')
                      ->orWhere('category_label', 'Umum');
                });
            } else {
                $query->where('category_label', $albumName);
            }

            // Search query inside album
            if ($request->filled('search')) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }

            // Type filter inside album
            if ($request->filled('type') && in_array($request->type, ['photo', 'video'])) {
                $query->where('type', $request->type);
            }

            $galleries = $query->orderBy('order')
                ->orderBy('id')
                ->paginate(24)
                ->withQueryString();

            return view('admin.galleries.index', [
                'mode' => 'media',
                'albumName' => $albumName,
                'galleries' => $galleries,
            ]);
        }


        $albumsQuery = Gallery::query();

        // Filter albums if search is submitted
        if ($request->filled('search')) {
            $search = $request->search;
            $albumsQuery->where(function ($q) use ($search) {
                $q->where('category_label', 'like', '%' . $search . '%');
                if (strtolower($search) === 'umum') {
                    $q->orWhereNull('category_label')
                      ->orWhere('category_label', '');
                }
            });
        }

        $albumsData = $albumsQuery->selectRaw("
                CASE 
                    WHEN category_label IS NULL OR TRIM(category_label) = '' THEN 'Umum' 
                    ELSE TRIM(category_label) 
                END as album_name,
                COUNT(*) as items_count,
                MAX(updated_at) as last_updated
            ")
            ->groupBy('album_name')
            ->orderBy('last_updated', 'desc')
            ->paginate(12, ['*'], 'albums_page')
            ->withQueryString();

        // Paginate cover extraction for efficiency
        $albums = collect($albumsData->items())->map(function ($item) {
            $name = $item->album_name;

            // Get cover image: find first photo or fallback to first video in this category
            $coverQuery = Gallery::query();
            if ($name === 'Umum') {
                $coverQuery->where(function ($q) {
                    $q->whereNull('category_label')
                      ->orWhere('category_label', '')
                      ->orWhere('category_label', 'Umum');
                });
            } else {
                $coverQuery->where('category_label', $name);
            }

            $coverItem = $coverQuery->orderBy('order')
                ->orderBy('id')
                ->first();

            return (object) [
                'name' => $name,
                'items_count' => $item->items_count,
                'cover_url' => $coverItem ? $coverItem->image_url : null,
                'last_updated' => $item->last_updated,
            ];
        });

        return view('admin.galleries.index', [
            'mode' => 'albums',
            'albums' => $albums,
            'albumsData' => $albumsData,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $categories = Gallery::query()->whereNotNull('category_label')->where('category_label', '!=', '')->distinct()->pluck('category_label');
        $activeAlbum = $request->query('album');
        
        return view('admin.galleries.create', compact('categories', 'activeAlbum'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('galleries', 'public');
        }

        Gallery::create($data);

        // Redirect back to the album if we came from one
        if ($request->filled('category_label')) {
            return redirect()->route('admin.galleries.index', ['album' => $request->category_label])
                ->with('status', 'Item galeri berhasil ditambahkan ke album.');
        }

        return redirect()->route('admin.galleries.index')->with('status', 'Item galeri berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $gallery = Gallery::findOrFail($id);
        return view('admin.galleries.show', compact('gallery'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $gallery = Gallery::findOrFail($id);
        $categories = Gallery::query()->whereNotNull('category_label')->where('category_label', '!=', '')->distinct()->pluck('category_label');

        return view('admin.galleries.edit', compact('gallery', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $gallery = Gallery::findOrFail($id);

        $data = $this->validateData($request, $gallery);

        if ($request->hasFile('image')) {
            if ($gallery->image && ! str_starts_with($gallery->image, 'images/')) {
                Storage::disk('public')->delete($gallery->image);
            }

            $data['image'] = $request->file('image')->store('galleries', 'public');
        }

        $gallery->update($data);

        // Redirect back to the album it belongs to
        $album = trim($gallery->category_label) ?: 'Umum';
        return redirect()->route('admin.galleries.index', ['album' => $album])
            ->with('status', 'Item galeri berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gallery = Gallery::findOrFail($id);
        $album = trim($gallery->category_label) ?: 'Umum';

        if ($gallery->image && ! str_starts_with($gallery->image, 'images/')) {
            Storage::disk('public')->delete($gallery->image);
        }

        $gallery->delete();

        return redirect()->route('admin.galleries.index', ['album' => $album])
            ->with('status', 'Item galeri berhasil dihapus.');
    }

    /**
     * Parse video link to extract platform and video ID.
     */
    private function parseVideoLink(string $link): ?array
    {
        $link = trim($link);
        if (empty($link)) {
            return null;
        }

        // 1. YouTube
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $link, $match)) {
            return [
                'platform' => 'youtube',
                'id' => $match[1],
            ];
        }

        // 2. Instagram
        if (preg_match('/instagram\.com\/(?:reel|p)\/([a-zA-Z0-9_-]+)/i', $link, $match)) {
            return [
                'platform' => 'instagram',
                'id' => $match[1],
            ];
        }

        // 3. Vimeo
        if (preg_match('/(?:vimeo\.com\/(?:channels\/[^\/]+\/|groups\/[^\/]+\/video\/|album\/[^\/]+\/video\/|video\/|)|player\.vimeo\.com\/video\/)([0-9]+)/i', $link, $match)) {
            return [
                'platform' => 'vimeo',
                'id' => $match[1],
            ];
        }

        return null;
    }

    /**
     * Validate the incoming request data.
     */
    private function validateData(Request $request, ?Gallery $gallery = null): array
    {
        $requiresImage = $gallery === null || ! $gallery->image;

        $rules = [
            'type' => ['required', 'in:photo,video'],
            'title' => ['required', 'string', 'max:255'],
            'category_label' => ['nullable', 'string', 'max:255'],
            'image' => [$requiresImage ? 'required' : 'nullable', 'image', 'max:2048'],
            'video_link' => ['nullable', 'required_if:type,video', 'string', 'url'],
            'is_active' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ];

        $data = $request->validate($rules);

        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = $data['order'] ?? 0;

        if ($data['type'] === 'video') {
            $parsed = $this->parseVideoLink($data['video_link'] ?? '');
            if (!$parsed) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'video_link' => ['Format link video tidak dikenali. Silakan gunakan URL YouTube, Instagram Reel, atau Vimeo yang valid.'],
                ]);
            }
            $data['video_id'] = $parsed['id'];
            $data['video_platform'] = $parsed['platform'];
        } else {
            $data['video_id'] = null;
            $data['video_platform'] = null;
        }

        unset($data['image']);
        unset($data['video_link']);

        return $data;
    }

    /**
     * Rename all galleries in a category to a new category label.
     */
    public function renameAlbum(Request $request)
    {
        $request->validate([
            'old_name' => ['required', 'string'],
            'new_name' => ['required', 'string', 'max:255'],
        ]);

        $oldName = $request->old_name;
        $newName = trim($request->new_name);

        if ($oldName === 'Umum' || $oldName === '') {
            Gallery::where(function ($q) {
                $q->whereNull('category_label')
                  ->orWhere('category_label', '')
                  ->orWhere('category_label', 'Umum');
            })->update([
                'category_label' => $newName
            ]);
        } else {
            Gallery::where('category_label', $oldName)->update([
                'category_label' => $newName
            ]);
        }

        return redirect()->route('admin.galleries.index', ['album' => $newName])
            ->with('status', 'Nama album berhasil diubah.');
    }

    /**
     * Delete an entire album and all its media items.
     */
    public function deleteAlbum(Request $request)
    {
        $request->validate([
            'album_name' => ['required', 'string'],
        ]);

        $albumName = trim($request->album_name);

        if ($albumName === 'Umum' || $albumName === '') {
            $galleries = Gallery::where(function ($q) {
                $q->whereNull('category_label')
                  ->orWhere('category_label', '')
                  ->orWhere('category_label', 'Umum');
            })->get();
        } else {
            $galleries = Gallery::where('category_label', $albumName)->get();
        }

        foreach ($galleries as $gallery) {
            if ($gallery->image && ! str_starts_with($gallery->image, 'images/')) {
                Storage::disk('public')->delete($gallery->image);
            }
            $gallery->delete();
        }

        return redirect()->route('admin.galleries.index')
            ->with('status', 'Album "' . $albumName . '" beserta seluruh isinya berhasil dihapus.');
    }
}
