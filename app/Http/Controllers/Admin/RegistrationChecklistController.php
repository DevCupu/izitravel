<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationChecklistController extends Controller
{
    /**
     * Update the status of a single checklist item (one of the 7 fixed types) for a registration.
     */
    public function update(Request $request, Registration $registration, string $type)
    {
        abort_unless(array_key_exists($type, Registration::ITEM_TYPES), 404);

        $data = $request->validate([
            'status' => ['required', 'string', 'in:'.implode(',', array_keys(Registration::STATUSES))],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $registration->items()->where('type', $type)->update($data);

        return redirect()->back()->with('status', 'Status berhasil diperbarui.');
    }
}
