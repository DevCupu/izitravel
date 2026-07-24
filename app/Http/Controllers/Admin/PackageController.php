<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->string('search')->toString();

        $packages = Package::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('airline', 'like', "%{$search}%")
                        ->orWhere('hotel', 'like', "%{$search}%");
                });
            })
            ->orderBy('order')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.packages.index', compact('packages', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('order')->get();

        return view('admin.packages.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('packages', 'public');
        }

        if ($request->hasFile('brochure')) {
            $data['brochure'] = $request->file('brochure')->store('packages/brochures', 'public');
        }

        if ($request->hasFile('brochure_image')) {
            $data['brochure_image'] = $request->file('brochure_image')->store('packages/brochures', 'public');
        }

        Package::create($data);

        return redirect()->route('admin.packages.index')->with('status', 'Paket umrah berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $package = Package::findOrFail($id);
        $categories = Category::where('is_active', true)->orderBy('order')->get();

        return view('admin.packages.edit', compact('package', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $package = Package::findOrFail($id);

        $data = $this->validateData($request);

        if ($request->hasFile('image')) {
            if ($package->image && ! str_starts_with($package->image, 'images/')) {
                Storage::disk('public')->delete($package->image);
            }

            $data['image'] = $request->file('image')->store('packages', 'public');
        }

        if ($request->hasFile('brochure')) {
            if ($package->brochure && ! str_starts_with($package->brochure, 'brochures/')) {
                Storage::disk('public')->delete($package->brochure);
            }

            $data['brochure'] = $request->file('brochure')->store('packages/brochures', 'public');
        }

        if ($request->hasFile('brochure_image')) {
            if ($package->brochure_image && ! str_starts_with($package->brochure_image, 'images/')) {
                Storage::disk('public')->delete($package->brochure_image);
            }

            $data['brochure_image'] = $request->file('brochure_image')->store('packages/brochures', 'public');
        }

        $package->update($data);

        return redirect()->route('admin.packages.index')->with('status', 'Paket umrah berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $package = Package::findOrFail($id);

        if ($package->image && ! str_starts_with($package->image, 'images/')) {
            Storage::disk('public')->delete($package->image);
        }

        if ($package->brochure && ! str_starts_with($package->brochure, 'brochures/')) {
            Storage::disk('public')->delete($package->brochure);
        }

        if ($package->brochure_image && ! str_starts_with($package->brochure_image, 'images/')) {
            Storage::disk('public')->delete($package->brochure_image);
        }

        $package->delete();

        return redirect()->route('admin.packages.index')->with('status', 'Paket umrah berhasil dihapus.');
    }

    /**
     * Validate the incoming request data.
     */
    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'duration_days' => ['required', 'integer', 'min:1', 'max:60'],
            'departure_date' => ['required', 'date'],
            'airline' => ['required', 'string', 'max:255'],
            'hotel' => ['nullable', 'string', 'max:255'],
            'hotel_makkah' => ['nullable', 'string', 'max:255'],
            'hotel_makkah_nights' => ['nullable', 'integer', 'min:1', 'max:30'],
            'hotel_madinah' => ['nullable', 'string', 'max:255'],
            'hotel_madinah_nights' => ['nullable', 'integer', 'min:1', 'max:30'],
            'inclusions' => ['nullable', 'array'],
            'inclusions.*.icon' => ['required_with:inclusions', 'string', 'max:50'],
            'inclusions.*.label' => ['required_with:inclusions', 'string', 'max:100'],
            'inclusions.*.desc' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:0'],
            'category' => ['nullable', 'string', 'max:50'],
            'badge_label' => ['nullable', 'string', 'max:50'],
            'badge_color' => ['nullable', 'string', 'in:amber,emerald,indigo,rose'],
            'image' => ['nullable', 'image', 'max:2048'],
            'brochure' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'brochure_image' => ['nullable', 'image', 'max:5120'],
            'is_active' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = $data['order'] ?? 0;

        // Build inclusions: filter out blank rows
        $rawInclusions = $request->input('inclusions', []);
        $data['inclusions'] = collect($rawInclusions)
            ->filter(fn ($item) => !empty($item['label']))
            ->values()
            ->toArray() ?: null;

        unset($data['image']);
        unset($data['brochure']);
        unset($data['brochure_image']);

        return $data;
    }
}
