<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jemaah;
use Illuminate\Http\Request;

class JemaahController extends Controller
{
    /**
     * JSON autocomplete for the "Tambah Jemaah" existing-jemaah picker.
     */
    public function search(Request $request)
    {
        $q = $request->string('q')->trim()->toString();

        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $jemaahs = Jemaah::where('name', 'like', "%{$q}%")
            ->orWhere('passport_number', 'like', "%{$q}%")
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'passport_number']);

        return response()->json($jemaahs);
    }

    /**
     * Full profile page: personal data + every departure (registration) this jemaah is part of.
     */
    public function show(Jemaah $jemaah)
    {
        $jemaah->load(['registrations' => function ($query) {
            $query->with(['package', 'items'])->latest();
        }]);

        return view('admin.jemaah.show', compact('jemaah'));
    }

    /**
     * Update a jemaah's personal data (biodata) — shared across all their registrations.
     */
    public function update(Request $request, Jemaah $jemaah)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'in:'.implode(',', array_keys(Jemaah::GENDERS))],
            'passport_number' => ['nullable', 'string', 'max:50', 'unique:jemaahs,passport_number,'.$jemaah->id],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:1000'],
        ]);

        $jemaah->update($data);

        return back()->with('status', 'Data jemaah berhasil diperbarui.');
    }
}
