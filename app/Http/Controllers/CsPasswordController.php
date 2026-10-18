<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class CsPasswordController extends Controller
{
    public function edit(): View
    {
        return view('cs.password');
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults(), 'different:current_password'],
        ]);

        $request->user()->update(['password' => $data['password'], 'must_change_password' => false]);
        $request->session()->regenerate();

        return to_route('cs.dashboard')->with('status', 'Kata sandi berhasil diganti.');
    }
}
