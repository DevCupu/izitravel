<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->string('search')->toString();

        $testimonials = Testimonial::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('message', 'like', "%{$search}%");
                });
            })
            ->orderBy('order')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.testimonials.index', compact('testimonials', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.testimonials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        Testimonial::create($data);

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimoni berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        return view('admin.testimonials.edit', compact('testimonial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $data = $this->validateData($request);

        $testimonial->update($data);

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimoni berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimoni berhasil dihapus.');
    }

    /**
     * Validate the incoming request data.
     */
    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'video_url' => ['nullable', 'url', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'is_active' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = $data['order'] ?? 0;

        return $data;
    }
}
