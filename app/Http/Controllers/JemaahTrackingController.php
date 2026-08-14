<?php

namespace App\Http\Controllers;

use App\Models\Jemaah;
use App\Models\Setting;
use Illuminate\Http\Request;

class JemaahTrackingController extends Controller
{
    /**
     * Search landing page — the passport + birth date form. Submitting redirects to result()
     * so results get their own spacious page instead of being crammed under the form.
     */
    public function index(Request $request)
    {
        $passport = $request->string('passport')->trim()->toString();
        $birthDate = $request->string('birth_date')->trim()->toString();

        if ($passport !== '' && $birthDate !== '') {
            return redirect()->route('public.jemaah.tracking.result', [
                'passport' => $passport,
                'birth_date' => $birthDate,
            ]);
        }

        $settings = Setting::pluck('value', 'key');

        return view('lacak-jemaah', compact('settings'));
    }

    /**
     * Public lookup result. Requires passport + birth date to both match together (not
     * passport alone) so a leaked/guessed passport number can't be used to pull up someone
     * else's data. Shows the jemaah's name, package details, departure date, one overall
     * status, and a headcount of fellow jemaah — no per-document detail, no other jemaah's
     * names.
     */
    public function result(Request $request)
    {
        $settings = Setting::pluck('value', 'key');
        $passport = $request->string('passport')->trim()->toString();
        $birthDate = $request->string('birth_date')->trim()->toString();

        if ($passport === '' || $birthDate === '') {
            return redirect()->route('public.jemaah.tracking');
        }

        $jemaah = Jemaah::where('passport_number', $passport)
            ->whereDate('birth_date', $birthDate)
            ->with(['registrations.package' => function ($query) {
                $query->withCount('registrations');
            }])
            ->first();

        return view('lacak-jemaah-hasil', compact('settings', 'passport', 'jemaah'));
    }
}
