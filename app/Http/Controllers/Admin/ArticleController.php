<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->toString();

        $articles = Article::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%")
                        ->orWhere('author', 'like', "%{$search}%");
                });
            })
            ->orderBy('order')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.articles.index', compact('articles', 'search'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = $request->input('slug') ? Str::slug($request->input('slug')) : Str::slug($data['title']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        Article::create($data);

        return redirect()->route('admin.articles.index')->with('status', 'Artikel berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $article = Article::findOrFail($id);
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, string $id)
    {
        $article = Article::findOrFail($id);
        $data = $this->validateData($request, $id);
        $data['slug'] = $request->input('slug') ? Str::slug($request->input('slug')) : Str::slug($data['title']);

        if ($request->hasFile('image')) {
            if ($article->image && ! str_starts_with($article->image, 'images/')) {
                Storage::disk('public')->delete($article->image);
            }
            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        $article->update($data);

        return redirect()->route('admin.articles.index')->with('status', 'Artikel berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $article = Article::findOrFail($id);

        if ($article->image && ! str_starts_with($article->image, 'images/')) {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete();

        return redirect()->route('admin.articles.index')->with('status', 'Artikel berhasil dihapus.');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'upload' => ['required', 'image', 'max:5120'],
        ]);

        if ($request->hasFile('upload')) {
            $path = $request->file('upload')->store('articles/media', 'public');
            $url = Storage::disk('public')->url($path);

            return response()->json([
                'url' => $url
            ]);
        }

        return response()->json([
            'error' => [
                'message' => 'Gagal mengunggah gambar.'
            ]
        ], 400);
    }

    private function validateData(Request $request, ?string $id = null): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', $id ? 'unique:articles,slug,' . $id : 'unique:articles,slug'],
            'category' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'author' => ['required', 'string', 'max:255'],
            'author_role' => ['nullable', 'string', 'max:255'],
            'read_time' => ['nullable', 'string', 'max:50'],
            'published_at' => ['nullable', 'string', 'max:50'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
            'tags' => ['nullable', 'string', 'max:255'],
        ];

        $data = $request->validate($rules);

        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = $data['order'] ?? 0;

        if ($request->filled('tags')) {
            $tagsArray = array_map(function($tag) {
                $tag = trim($tag);
                if (!empty($tag) && !str_starts_with($tag, '#')) {
                    $tag = '#' . $tag;
                }
                return $tag;
            }, explode(',', $request->input('tags')));
            $data['tags'] = implode(', ', array_filter($tagsArray));
        } else {
            $data['tags'] = null;
        }

        unset($data['image']);

        return $data;
    }
}
