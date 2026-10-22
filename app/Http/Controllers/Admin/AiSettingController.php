<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\AiRecommendationSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class AiSettingController extends Controller
{
    public function index()
    {
        return view('admin.ai-settings.index', ['ai' => AiRecommendationSettings::current()]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'enabled' => ['nullable', 'boolean'],
            'api_key' => ['nullable', 'string', 'max:2000'],
            'clear_api_key' => ['nullable', 'boolean'],
            'model' => ['required', 'string', 'max:80', 'regex:/^[A-Za-z0-9._-]+$/'],
            'guidance' => ['nullable', 'string', 'max:1000'],
            'candidate_limit' => ['required', 'integer', 'between:6,30'],
            'result_limit' => ['required', 'integer', 'between:1,5'],
            'daily_limit' => ['required', 'integer', 'between:0,10000'],
            'timeout_seconds' => ['required', 'integer', 'between:3,30'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->except('api_key'));
        }

        $data = $validator->validated();

        foreach (['model', 'guidance', 'candidate_limit', 'result_limit', 'daily_limit', 'timeout_seconds'] as $field) {
            Setting::setValue('ai_recommendation_'.$field, (string) ($data[$field] ?? ''));
        }

        Setting::setValue('ai_recommendation_enabled', $request->boolean('enabled') ? '1' : '0');

        if ($request->boolean('clear_api_key')) {
            Setting::setValue('ai_recommendation_api_key', '');
        } elseif (trim((string) ($data['api_key'] ?? '')) !== '') {
            Setting::setValue('ai_recommendation_api_key', Crypt::encryptString(trim($data['api_key'])));
        }

        $ai = AiRecommendationSettings::current();
        if (! $ai['key_configured']) {
            AiRecommendationSettings::invalidateVerification();

            return redirect()->route('admin.ai-settings.index')->with('status', 'Pengaturan tersimpan. Tambahkan API key agar pencarian AI bisa ditampilkan.');
        }

        $verified = $ai['key_verified'] || AiRecommendationSettings::verifyConnection();

        return redirect()->route('admin.ai-settings.index')->with('status', $verified
            ? 'Pengaturan AI tersimpan dan koneksi berhasil diverifikasi.'
            : 'Pengaturan tersimpan, tetapi koneksi API key atau model belum berhasil diverifikasi. Pencarian AI disembunyikan sampai koneksi berhasil.');
    }

    public function verify()
    {
        $verified = AiRecommendationSettings::verifyConnection();

        return redirect()->route('admin.ai-settings.index')->with('status', $verified
            ? 'Koneksi OpenAI berhasil diverifikasi. Pencarian AI siap ditampilkan.'
            : 'Koneksi OpenAI belum berhasil diverifikasi. Periksa API key, model, dan koneksi server.');
    }
}
