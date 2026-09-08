<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use App\Support\ImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->toString();

        $promos = Promo::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('label', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy('order')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.promos.index', compact('promos', 'search'));
    }

    public function create()
    {
        return view('admin.promos.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('image')) {
            $data['image'] = ImageOptimizer::optimize($request->file('image'), 'promos', 1600);
        }

        Promo::create($data);

        return redirect()->route('admin.promos.index')->with('status', 'Promo carousel berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $promo = Promo::findOrFail($id);

        return view('admin.promos.edit', compact('promo'));
    }

    public function update(Request $request, string $id)
    {
        $promo = Promo::findOrFail($id);

        $data = $this->validateData($request);

        if ($request->hasFile('image')) {
            if ($promo->image && ! str_starts_with($promo->image, 'images/')) {
                Storage::disk('public')->delete($promo->image);
            }
            $data['image'] = ImageOptimizer::optimize($request->file('image'), 'promos', 1600);
        }

        $promo->update($data);

        return redirect()->route('admin.promos.index')->with('status', 'Promo carousel berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $promo = Promo::findOrFail($id);

        if ($promo->image && ! str_starts_with($promo->image, 'images/')) {
            Storage::disk('public')->delete($promo->image);
        }

        $promo->delete();

        return redirect()->route('admin.promos.index')->with('status', 'Promo carousel berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'label' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:4096'],
            'cta_text' => ['nullable', 'string', 'max:255'],
            'action_url' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = $data['order'] ?? 0;

        unset($data['image']);

        return $data;
    }
}