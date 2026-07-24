<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->toString();

        $partners = Partner::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('order')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.partners.index', compact('partners', 'search'));
    }

    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if ($data['logo_type'] === 'image' && $request->hasFile('image')) {
            $data['logo_path'] = $request->file('image')->store('partners', 'public');
        } elseif ($data['logo_type'] === 'svg') {
            $data['logo_path'] = $request->input('svg_code');
        }

        Partner::create($data);

        return redirect()->route('admin.partners.index')->with('status', 'Mitra maskapai berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $partner = Partner::findOrFail($id);
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, string $id)
    {
        $partner = Partner::findOrFail($id);
        $data = $this->validateData($request);

        if ($data['logo_type'] === 'image') {
            if ($request->hasFile('image')) {
                if ($partner->logo_path && ! str_starts_with($partner->logo_path, 'images/')) {
                    Storage::disk('public')->delete($partner->logo_path);
                }
                $data['logo_path'] = $request->file('image')->store('partners', 'public');
            } else {
                $data['logo_path'] = $partner->logo_path;
            }
        } elseif ($data['logo_type'] === 'svg') {
            $data['logo_path'] = $request->input('svg_code');
        }

        $partner->update($data);

        return redirect()->route('admin.partners.index')->with('status', 'Mitra maskapai berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $partner = Partner::findOrFail($id);

        if ($partner->logo_type === 'image' && $partner->logo_path && ! str_starts_with($partner->logo_path, 'images/')) {
            Storage::disk('public')->delete($partner->logo_path);
        }

        $partner->delete();

        return redirect()->route('admin.partners.index')->with('status', 'Mitra maskapai berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'logo_type' => ['required', 'string', 'in:image,svg'],
            'image' => ['nullable', 'image', 'max:2048'],
            'svg_code' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = $data['order'] ?? 0;

        unset($data['image']);
        unset($data['svg_code']);

        return $data;
    }
}
