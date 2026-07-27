<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $keys = [
            'site_name',
            'site_tagline',
            'site_description',
            'seo_google_analytics_id',
            'seo_meta_keywords',
            'terms_content',
            'privacy_content',
            'about_title',
            'about_description',
            'about_satisfaction_rate',
            'about_departed_count',
            'about_vision',
            'about_mission',
            'contact_whatsapp',
            'contact_whatsapp_cofounder',
            'contact_phone',
            'contact_email',
            'contact_address',
            'contact_gmaps',
            'office_hours',
            'social_facebook',
            'social_instagram',
            'social_youtube',
            'social_tiktok',
            'social_twitter',
            'registration_step_1_title', 'registration_step_1_description',
            'registration_step_2_title', 'registration_step_2_description',
            'registration_step_3_title', 'registration_step_3_description',
            'registration_step_4_title', 'registration_step_4_description',
            'registration_step_5_title', 'registration_step_5_description',
            'registration_step_6_title', 'registration_step_6_description',
            'package_note_1',
            'package_note_2',
            'package_note_3',
            'hero_calligraphy',
            'hero_badge',
            'hero_badge_1',
            'hero_badge_2',
            'hero_badge_3',
            'hero_stat_title',
            'hero_stat_subtitle',
            'hero_stat_value',
            'features_badge',
            'features_section_title',
            'features_section_subtitle',
            'feature_1_title', 'feature_1_desc', 'feature_1_icon',
            'feature_2_title', 'feature_2_desc', 'feature_2_icon',
            'feature_3_title', 'feature_3_desc', 'feature_3_icon',
            'feature_4_title', 'feature_4_desc', 'feature_4_icon',
            'feature_5_title', 'feature_5_desc', 'feature_5_icon',
            'feature_6_title', 'feature_6_desc', 'feature_6_icon',
            'registration_step_1_icon', 'registration_step_2_icon', 'registration_step_3_icon',
            'registration_step_4_icon', 'registration_step_5_icon', 'registration_step_6_icon',
            // Hero trust indicators
            'trust_icon_1', 'trust_icon_2', 'trust_icon_3',
            'trust_card_1_title', 'trust_card_1_subtitle', 'trust_card_1_icon',
            'trust_card_2_title', 'trust_card_2_subtitle', 'trust_card_2_icon',
            'trust_card_3_title', 'trust_card_3_subtitle', 'trust_card_3_icon',
            'trust_card_4_title', 'trust_card_4_subtitle', 'trust_card_4_icon',
            // About extra labels
            'about_badge', 'about_vision_label', 'about_mission_label',
            'about_stat_1_label', 'about_stat_2_label', 'about_ppiu_label',
            // Section headings & labels
            'packages_label', 'packages_section_title', 'packages_section_subtitle',
            'packages_price_label', 'packages_detail_btn',
            'gallery_label', 'gallery_section_title', 'gallery_section_subtitle',
            'testimonials_label', 'testimonials_section_title', 'testimonials_section_subtitle',
            'articles_label', 'articles_section_title', 'articles_section_subtitle',
            'articles_read_more', 'articles_read_suffix',
            'articles_filter_all', 'articles_filter_haramain', 'articles_filter_panduan', 'articles_filter_tips',
            'team_section_title', 'team_section_subtitle', 'team_other_section_label',
            'partners_section_title', 'partners_extra',
            'faq_section_title',
            'registration_title', 'registration_subtitle',
            // Partnership (Kemitraan) section
            'partnership_badge', 'partnership_title', 'partnership_subtitle', 'partnership_reg_label',
            'partnership_cta_title', 'partnership_cta_desc', 'partnership_cta_button',
            'partnership_tier_1_badge', 'partnership_tier_1_title', 'partnership_tier_1_price',
            'partnership_tier_1_feature_1', 'partnership_tier_1_feature_2', 'partnership_tier_1_feature_3', 'partnership_tier_1_feature_4',
            'partnership_tier_2_badge', 'partnership_tier_2_title', 'partnership_tier_2_price',
            'partnership_tier_2_feature_1', 'partnership_tier_2_feature_2', 'partnership_tier_2_feature_3', 'partnership_tier_2_feature_4',
            'partnership_tier_3_title', 'partnership_tier_3_subtitle',
            'partnership_reward_1_label', 'partnership_reward_1_value', 'partnership_reward_1_desc',
            'partnership_reward_2_label', 'partnership_reward_2_value', 'partnership_reward_2_desc',
            // CTA section
            'cta_title', 'cta_description', 'cta_button', 'cta_packages_label', 'cta_consultation_label',
            // Footer
            'footer_contact_heading', 'footer_ppiu_number',
            // Haramain Live & Info Center settings
            'haramain_density_base',
            'haramain_youtube_makkah',
            'haramain_youtube_madinah',
            'haramain_badge',
            'haramain_title',
            'haramain_subtitle',
            // Extended SEO
            'seo_google_console_verification',
            'seo_bing_verification',
            'seo_author',
            'seo_canonical_url',
            'seo_og_title',
            'seo_og_description',
            'seo_robots_txt',
            'seo_sitemap_enabled',
        ];

        $request->validate([
            'site_name'              => ['nullable', 'string', 'max:100'],
            'site_tagline'           => ['nullable', 'string', 'max:150'],
            'site_description'       => ['nullable', 'string', 'max:300'],
            'terms_content'          => ['nullable', 'string'],
            'privacy_content'        => ['nullable', 'string'],
            'seo_google_analytics_id'=> ['nullable', 'string', 'max:50'],
            'seo_meta_keywords'      => ['nullable', 'string', 'max:500'],
            'seo_google_console_verification' => ['nullable', 'string', 'max:255'],
            'seo_bing_verification'      => ['nullable', 'string', 'max:255'],
            'seo_author'                 => ['nullable', 'string', 'max:100'],
            'seo_canonical_url'          => ['nullable', 'url', 'max:255'],
            'seo_og_title'               => ['nullable', 'string', 'max:150'],
            'seo_og_description'         => ['nullable', 'string', 'max:500'],
            'seo_robots_txt'             => ['nullable', 'string', 'max:5000'],
            'seo_sitemap_enabled'        => ['nullable', 'boolean'],
            'seo_og_image'               => ['nullable', 'image', 'max:2048'],
            'about_title'            => ['nullable', 'string', 'max:200'],
            'about_description'      => ['nullable', 'string'],
            'about_satisfaction_rate'=> ['nullable', 'integer', 'min:0', 'max:100'],
            'about_departed_count'   => ['nullable', 'integer', 'min:0'],
            'about_vision'           => ['nullable', 'string'],
            'about_mission'          => ['nullable', 'string'],
            'contact_whatsapp'       => ['nullable', 'string', 'max:30', 'regex:/^[0-9+\s\-]+$/'],
            'contact_whatsapp_cofounder' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+\s\-]+$/'],
            'contact_phone'          => ['nullable', 'string', 'max:30'],
            'contact_email'          => ['nullable', 'email', 'max:100'],
            'contact_address'        => ['nullable', 'string', 'max:500'],
            'contact_gmaps'          => ['nullable', 'string'],
            'office_hours'           => ['nullable', 'string', 'max:100'],
            'social_facebook'        => ['nullable', 'url', 'max:255'],
            'social_instagram'       => ['nullable', 'url', 'max:255'],
            'social_youtube'         => ['nullable', 'url', 'max:255'],
            'social_tiktok'          => ['nullable', 'url', 'max:255'],
            'social_twitter'         => ['nullable', 'url', 'max:255'],
            'registration_step_1_title'       => ['nullable', 'string', 'max:100'],
            'registration_step_1_description' => ['nullable', 'string', 'max:255'],
            'registration_step_2_title'       => ['nullable', 'string', 'max:100'],
            'registration_step_2_description' => ['nullable', 'string', 'max:255'],
            'registration_step_3_title'       => ['nullable', 'string', 'max:100'],
            'registration_step_3_description' => ['nullable', 'string', 'max:255'],
            'registration_step_4_title'       => ['nullable', 'string', 'max:100'],
            'registration_step_4_description' => ['nullable', 'string', 'max:255'],
            'registration_step_5_title'       => ['nullable', 'string', 'max:100'],
            'registration_step_5_description' => ['nullable', 'string', 'max:255'],
            'registration_step_6_title'       => ['nullable', 'string', 'max:100'],
            'registration_step_6_description' => ['nullable', 'string', 'max:255'],
            'package_note_1'         => ['nullable', 'string', 'max:500'],
            'package_note_2'         => ['nullable', 'string', 'max:500'],
            'package_note_3'         => ['nullable', 'string', 'max:500'],
            'hero_calligraphy'       => ['nullable', 'string', 'max:150'],
            'hero_badge'             => ['nullable', 'string', 'max:150'],
            'hero_badge_1'           => ['nullable', 'string', 'max:150'],
            'hero_badge_2'           => ['nullable', 'string', 'max:150'],
            'hero_badge_3'           => ['nullable', 'string', 'max:150'],
            'hero_stat_title'        => ['nullable', 'string', 'max:100'],
            'hero_stat_subtitle'     => ['nullable', 'string', 'max:100'],
            'hero_stat_value'        => ['nullable', 'string', 'max:50'],
            'features_badge'         => ['nullable', 'string', 'max:100'],
            'features_section_title' => ['nullable', 'string', 'max:150'],
            'features_section_subtitle' => ['nullable', 'string', 'max:255'],
            'feature_1_title'        => ['nullable', 'string', 'max:100'],
            'feature_1_desc'         => ['nullable', 'string', 'max:255'],
            'feature_2_title'        => ['nullable', 'string', 'max:100'],
            'feature_2_desc'         => ['nullable', 'string', 'max:255'],
            'feature_3_title'        => ['nullable', 'string', 'max:100'],
            'feature_3_desc'         => ['nullable', 'string', 'max:255'],
            'feature_4_title'        => ['nullable', 'string', 'max:100'],
            'feature_4_desc'         => ['nullable', 'string', 'max:255'],
            'feature_5_title'        => ['nullable', 'string', 'max:100'],
            'feature_5_desc'         => ['nullable', 'string', 'max:255'],
            'feature_6_title'        => ['nullable', 'string', 'max:100'],
            'feature_6_desc'         => ['nullable', 'string', 'max:255'],
            'feature_1_icon'         => ['nullable', 'string', 'max:50'],
            'feature_2_icon'         => ['nullable', 'string', 'max:50'],
            'feature_3_icon'         => ['nullable', 'string', 'max:50'],
            'feature_4_icon'         => ['nullable', 'string', 'max:50'],
            'feature_5_icon'         => ['nullable', 'string', 'max:50'],
            'feature_6_icon'         => ['nullable', 'string', 'max:50'],
            'registration_step_1_icon' => ['nullable', 'string', 'max:50'],
            'registration_step_2_icon' => ['nullable', 'string', 'max:50'],
            'registration_step_3_icon' => ['nullable', 'string', 'max:50'],
            'registration_step_4_icon' => ['nullable', 'string', 'max:50'],
            'registration_step_5_icon' => ['nullable', 'string', 'max:50'],
            'registration_step_6_icon' => ['nullable', 'string', 'max:50'],
            'trust_icon_1'           => ['nullable', 'string', 'max:100'],
            'trust_icon_2'           => ['nullable', 'string', 'max:100'],
            'trust_icon_3'           => ['nullable', 'string', 'max:100'],
            'trust_card_1_title'     => ['nullable', 'string', 'max:100'],
            'trust_card_1_subtitle'  => ['nullable', 'string', 'max:100'],
            'trust_card_1_icon'      => ['nullable', 'string', 'max:50'],
            'trust_card_2_title'     => ['nullable', 'string', 'max:100'],
            'trust_card_2_subtitle'  => ['nullable', 'string', 'max:100'],
            'trust_card_2_icon'      => ['nullable', 'string', 'max:50'],
            'trust_card_3_title'     => ['nullable', 'string', 'max:100'],
            'trust_card_3_subtitle'  => ['nullable', 'string', 'max:100'],
            'trust_card_3_icon'      => ['nullable', 'string', 'max:50'],
            'trust_card_4_title'     => ['nullable', 'string', 'max:100'],
            'trust_card_4_subtitle'  => ['nullable', 'string', 'max:100'],
            'trust_card_4_icon'      => ['nullable', 'string', 'max:50'],
            'about_badge'            => ['nullable', 'string', 'max:100'],
            'about_vision_label'     => ['nullable', 'string', 'max:100'],
            'about_mission_label'    => ['nullable', 'string', 'max:100'],
            'about_stat_1_label'     => ['nullable', 'string', 'max:100'],
            'about_stat_2_label'     => ['nullable', 'string', 'max:100'],
            'about_ppiu_label'       => ['nullable', 'string', 'max:100'],
            'packages_label'         => ['nullable', 'string', 'max:100'],
            'packages_section_title' => ['nullable', 'string', 'max:150'],
            'packages_section_subtitle' => ['nullable', 'string', 'max:255'],
            'packages_price_label'   => ['nullable', 'string', 'max:100'],
            'packages_detail_btn'    => ['nullable', 'string', 'max:100'],
            'gallery_label'          => ['nullable', 'string', 'max:100'],
            'gallery_section_title'  => ['nullable', 'string', 'max:150'],
            'gallery_section_subtitle' => ['nullable', 'string', 'max:255'],
            'testimonials_label'     => ['nullable', 'string', 'max:100'],
            'testimonials_section_title' => ['nullable', 'string', 'max:150'],
            'testimonials_section_subtitle' => ['nullable', 'string', 'max:255'],
            'articles_label'         => ['nullable', 'string', 'max:100'],
            'articles_section_title' => ['nullable', 'string', 'max:150'],
            'articles_section_subtitle' => ['nullable', 'string', 'max:255'],
            'articles_read_more'     => ['nullable', 'string', 'max:100'],
            'articles_read_suffix'   => ['nullable', 'string', 'max:100'],
            'articles_filter_all'    => ['nullable', 'string', 'max:50'],
            'articles_filter_haramain' => ['nullable', 'string', 'max:50'],
            'articles_filter_panduan' => ['nullable', 'string', 'max:50'],
            'articles_filter_tips'   => ['nullable', 'string', 'max:50'],
            'team_section_title'     => ['nullable', 'string', 'max:150'],
            'team_section_subtitle'  => ['nullable', 'string', 'max:255'],
            'team_other_section_label' => ['nullable', 'string', 'max:150'],
            'partners_section_title' => ['nullable', 'string', 'max:150'],
            'partners_extra'         => ['nullable', 'string', 'max:255'],
            'faq_section_title'      => ['nullable', 'string', 'max:150'],
            'registration_title'     => ['nullable', 'string', 'max:150'],
            'registration_subtitle'  => ['nullable', 'string', 'max:255'],
            'partnership_badge'      => ['nullable', 'string', 'max:100'],
            'partnership_title'      => ['nullable', 'string', 'max:150'],
            'partnership_subtitle'   => ['nullable', 'string', 'max:500'],
            'partnership_reg_label'  => ['nullable', 'string', 'max:100'],
            'partnership_cta_title'  => ['nullable', 'string', 'max:150'],
            'partnership_cta_desc'   => ['nullable', 'string', 'max:500'],
            'partnership_cta_button' => ['nullable', 'string', 'max:100'],
            'partnership_tier_1_badge'      => ['nullable', 'string', 'max:100'],
            'partnership_tier_1_title'      => ['nullable', 'string', 'max:150'],
            'partnership_tier_1_price'      => ['nullable', 'string', 'max:100'],
            'partnership_tier_1_feature_1'  => ['nullable', 'string', 'max:255'],
            'partnership_tier_1_feature_2'  => ['nullable', 'string', 'max:255'],
            'partnership_tier_1_feature_3'  => ['nullable', 'string', 'max:255'],
            'partnership_tier_1_feature_4'  => ['nullable', 'string', 'max:255'],
            'partnership_tier_2_badge'      => ['nullable', 'string', 'max:100'],
            'partnership_tier_2_title'      => ['nullable', 'string', 'max:150'],
            'partnership_tier_2_price'      => ['nullable', 'string', 'max:100'],
            'partnership_tier_2_feature_1'  => ['nullable', 'string', 'max:255'],
            'partnership_tier_2_feature_2'  => ['nullable', 'string', 'max:255'],
            'partnership_tier_2_feature_3'  => ['nullable', 'string', 'max:255'],
            'partnership_tier_2_feature_4'  => ['nullable', 'string', 'max:255'],
            'partnership_tier_3_title'      => ['nullable', 'string', 'max:150'],
            'partnership_tier_3_subtitle'   => ['nullable', 'string', 'max:255'],
            'partnership_reward_1_label'    => ['nullable', 'string', 'max:150'],
            'partnership_reward_1_value'    => ['nullable', 'string', 'max:150'],
            'partnership_reward_1_desc'     => ['nullable', 'string', 'max:255'],
            'partnership_reward_2_label'    => ['nullable', 'string', 'max:150'],
            'partnership_reward_2_value'    => ['nullable', 'string', 'max:150'],
            'partnership_reward_2_desc'     => ['nullable', 'string', 'max:255'],
            'cta_title'              => ['nullable', 'string', 'max:150'],
            'cta_description'        => ['nullable', 'string', 'max:500'],
            'cta_button'             => ['nullable', 'string', 'max:100'],
            'cta_packages_label'     => ['nullable', 'string', 'max:100'],
            'cta_consultation_label' => ['nullable', 'string', 'max:100'],
            'footer_contact_heading' => ['nullable', 'string', 'max:100'],
            'footer_ppiu_number'     => ['nullable', 'string', 'max:150'],
            'haramain_density_base'  => ['nullable', 'integer', 'min:0'],
            'haramain_youtube_makkah' => ['nullable', 'string', 'max:255'],
            'haramain_youtube_madinah' => ['nullable', 'string', 'max:255'],
            'haramain_badge'         => ['nullable', 'string', 'max:150'],
            'haramain_title'         => ['nullable', 'string', 'max:150'],
            'haramain_subtitle'      => ['nullable', 'string', 'max:255'],
            'site_logo'              => ['nullable', 'image', 'max:2048'],
            'site_favicon'           => ['nullable', 'image', 'max:2048'],
            'hero_image'             => ['nullable', 'image', 'max:4096'],
            'about_image_1'          => ['nullable', 'image', 'max:4096'],
            'about_image_2'          => ['nullable', 'image', 'max:4096'],
            'hero_badge_1_image'     => ['nullable', 'image', 'max:1024'],
            'hero_badge_2_image'     => ['nullable', 'image', 'max:1024'],
            'hero_badge_3_image'     => ['nullable', 'image', 'max:1024'],
            'feature_1_image'        => ['nullable', 'image', 'max:2048'],
            'feature_2_image'        => ['nullable', 'image', 'max:2048'],
            'feature_3_image'        => ['nullable', 'image', 'max:2048'],
            'feature_4_image'        => ['nullable', 'image', 'max:2048'],
            'feature_5_image'        => ['nullable', 'image', 'max:2048'],
            'feature_6_image'        => ['nullable', 'image', 'max:2048'],
        ]);

        foreach ($keys as $key) {
            if ($request->has($key)) {
                $val = $request->input($key);
                if (in_array($key, ['contact_whatsapp', 'contact_whatsapp_cofounder']) && !empty($val)) {
                    $val = preg_replace('/[^0-9]/', '', $val);
                }
                Setting::setValue($key, $val);
            }
        }

        // Handle sitemap toggle manually as it is a checkbox
        Setting::setValue('seo_sitemap_enabled', $request->has('seo_sitemap_enabled') ? '1' : '0');

        if ($request->hasFile('site_logo')) {
            $oldLogo = Setting::getValue('site_logo');
            if ($oldLogo && ! str_starts_with($oldLogo, 'images/')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldLogo);
            }
            $logoPath = $request->file('site_logo')->store('settings', 'public');
            Setting::setValue('site_logo', $logoPath);
        }

        if ($request->hasFile('site_favicon')) {
            $oldFavicon = Setting::getValue('site_favicon');
            if ($oldFavicon && ! str_starts_with($oldFavicon, 'images/')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldFavicon);
            }
            $faviconPath = $request->file('site_favicon')->store('settings', 'public');
            Setting::setValue('site_favicon', $faviconPath);
        }

        if ($request->hasFile('hero_image')) {
            $oldHeroImage = Setting::getValue('hero_image');
            if ($oldHeroImage && ! str_starts_with($oldHeroImage, 'images/')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldHeroImage);
            }
            $heroImagePath = $request->file('hero_image')->store('settings', 'public');
            Setting::setValue('hero_image', $heroImagePath);
        }

        // About collage images + hero floating badge images + Open Graph image
        foreach (['about_image_1', 'about_image_2', 'hero_badge_1_image', 'hero_badge_2_image', 'hero_badge_3_image', 'seo_og_image'] as $field) {
            if ($request->boolean($field . '_remove')) {
                $old = Setting::getValue($field);
                if ($old && ! str_starts_with($old, 'images/')) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($old);
                }
                Setting::setValue($field, '');
                continue;
            }

            if ($request->hasFile($field)) {
                $old = Setting::getValue($field);
                if ($old && ! str_starts_with($old, 'images/')) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($old);
                }
                $path = $request->file($field)->store('settings', 'public');
                Setting::setValue($field, $path);
            }
        }

        // Feature card images (replace icon when set)
        for ($f = 1; $f <= 6; $f++) {
            $field = 'feature_' . $f . '_image';

            // Remove existing image when the "hapus" checkbox is ticked
            if ($request->boolean($field . '_remove')) {
                $old = Setting::getValue($field);
                if ($old && ! str_starts_with($old, 'images/')) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($old);
                }
                Setting::setValue($field, '');
                continue;
            }

            if ($request->hasFile($field)) {
                $old = Setting::getValue($field);
                if ($old && ! str_starts_with($old, 'images/')) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($old);
                }
                $path = $request->file($field)->store('settings', 'public');
                Setting::setValue($field, $path);
            }
        }

        return redirect()->route('admin.settings.index')->with('status', 'Pengaturan website berhasil diperbarui.');
    }
}
