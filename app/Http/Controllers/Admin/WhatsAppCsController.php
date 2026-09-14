<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhatsAppCs;
use Illuminate\Http\Request;

class WhatsAppCsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->toString();

        $csList = WhatsAppCs::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->orderBy('is_active', 'desc')
            ->orderBy('weight', 'desc')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.whatsapp-cs.index', compact('csList', 'search'));
    }

    public function create()
    {
        return view('admin.whatsapp-cs.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $activeCount = WhatsAppCs::where('is_active', true)->count();
        if ($activeCount === 0 && ! $data['is_active']) {
            return redirect()->route('admin.whatsapp-cs.index')->with('status', 'Minimal satu CS harus aktif.');
        }

        WhatsAppCs::create($data);

        return redirect()->route('admin.whatsapp-cs.index')->with('status', 'CS WhatsApp berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $cs = WhatsAppCs::findOrFail($id);

        return view('admin.whatsapp-cs.edit', compact('cs'));
    }

    public function update(Request $request, string $id)
    {
        $cs = WhatsAppCs::findOrFail($id);
        $data = $this->validateData($request, $cs);

        if (! $data['is_active'] && $cs->is_active) {
            $otherActive = WhatsAppCs::where('is_active', true)->where('id', '!=', $cs->id)->count();
            if ($otherActive === 0) {
                return redirect()->route('admin.whatsapp-cs.edit', $cs->id)
                    ->withInput()
                    ->with('status', 'Tidak bisa menonaktifkan CS terakhir yang aktif.');
            }
        }

        $cs->update($data);

        return redirect()->route('admin.whatsapp-cs.index')->with('status', 'CS WhatsApp berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $cs = WhatsAppCs::findOrFail($id);

        if ($cs->is_active && WhatsAppCs::where('is_active', true)->count() === 1) {
            return redirect()->route('admin.whatsapp-cs.index')->with('status', 'Tidak bisa menghapus CS terakhir yang aktif.');
        }

        $cs->delete();

        return redirect()->route('admin.whatsapp-cs.index')->with('status', 'CS WhatsApp berhasil dihapus.');
    }

    private function validateData(Request $request, ?WhatsAppCs $cs = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^[0-9+\s\-]+$/'],
            'weight' => ['required', 'integer', 'min:1', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $phone = preg_replace('/[^0-9]/', '', $data['phone']);
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }
        $data['phone'] = $phone;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
