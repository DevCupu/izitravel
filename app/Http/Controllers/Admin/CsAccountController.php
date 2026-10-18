<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WhatsAppCs;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CsAccountController extends Controller
{
    public function store(Request $request, WhatsAppCs $cs): RedirectResponse
    {
        abort_if($cs->user_id, 409);
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
        ]);
        $temporaryPassword = Str::password(16);
        DB::transaction(function () use ($cs, $data, $temporaryPassword) {
            $user = User::create([
                'name' => $cs->name,
                'email' => $data['email'],
                'password' => $temporaryPassword,
                'role' => 'cs',
                'is_active' => true,
                'must_change_password' => true,
                'email_verified_at' => now(),
            ]);
            $cs->update(['user_id' => $user->id]);
        });

        return to_route('admin.whatsapp-cs.edit', $cs)->with('temporary_password', $temporaryPassword)
            ->with('status', 'Akun CS dibuat. Salin kata sandi sementara sekarang; hanya tampil sekali.');
    }

    public function resetPassword(WhatsAppCs $cs): RedirectResponse
    {
        abort_unless($cs->user, 404);
        $temporaryPassword = Str::password(16);
        $cs->user->update(['password' => $temporaryPassword, 'must_change_password' => true]);

        return to_route('admin.whatsapp-cs.edit', $cs)->with('temporary_password', $temporaryPassword)
            ->with('status', 'Kata sandi sementara baru dibuat. Salin sekarang; hanya tampil sekali.');
    }

    public function updateEmail(Request $request, WhatsAppCs $cs): RedirectResponse
    {
        abort_unless($cs->user, 404);
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($cs->user)],
        ]);
        $cs->user->update(['email' => $data['email'], 'email_verified_at' => now()]);

        return to_route('admin.whatsapp-cs.edit', $cs)->with('status', 'Email login CS diperbarui.');
    }

    public function toggle(Request $request, WhatsAppCs $cs): RedirectResponse
    {
        abort_unless($cs->user, 404);
        $data = $request->validate(['is_active' => ['required', 'boolean']]);
        $cs->user->update(['is_active' => (bool) $data['is_active']]);

        return to_route('admin.whatsapp-cs.edit', $cs)->with('status', $data['is_active'] ? 'Akun CS diaktifkan.' : 'Akun CS dinonaktifkan.');
    }
}
