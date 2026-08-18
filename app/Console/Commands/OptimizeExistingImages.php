<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Models\Team;
use App\Models\Testimonial;
use App\Support\ImageOptimizer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class OptimizeExistingImages extends Command
{
    protected $signature = 'images:optimize-existing';

    protected $description = 'Resize and convert already-uploaded team/testimonial/settings images to WebP';

    /** Settings image keys and their max dimension. */
    private const SETTING_FIELDS = [
        'site_logo' => 300,
        'site_favicon' => 300,
        'hero_image' => 1920,
        'about_image_1' => 1000,
        'about_image_2' => 1000,
        'hero_badge_1_image' => 1000,
        'hero_badge_2_image' => 1000,
        'hero_badge_3_image' => 1000,
        'about_ppiu_logo' => 1000,
        'feature_1_image' => 1000,
        'feature_2_image' => 1000,
        'feature_3_image' => 1000,
        'feature_4_image' => 1000,
        'feature_5_image' => 1000,
        'feature_6_image' => 1000,
        'seo_og_image' => 1200,
    ];

    public function handle(): int
    {
        $count = 0;

        foreach (Team::whereNotNull('image')->where('image', '!=', '')->get() as $team) {
            if ($this->reprocess($team->image, 'teams', 400, fn ($path) => $team->update(['image' => $path]))) {
                $count++;
            }
        }

        foreach (Testimonial::whereNotNull('photo')->where('photo', '!=', '')->get() as $testimonial) {
            if ($this->reprocess($testimonial->photo, 'testimonials', 400, fn ($path) => $testimonial->update(['photo' => $path]))) {
                $count++;
            }
        }

        foreach (self::SETTING_FIELDS as $key => $maxDimension) {
            $value = Setting::getValue($key);
            if (! $value) {
                continue;
            }

            if ($this->reprocess($value, 'settings', $maxDimension, fn ($path) => Setting::setValue($key, $path))) {
                $count++;
            }
        }

        $this->info("Optimized {$count} image(s).");

        return self::SUCCESS;
    }

    private function reprocess(string $path, string $directory, int $maxDimension, callable $save): bool
    {
        // Bundled fallback assets under public/images/ aren't user uploads — skip them.
        if (str_starts_with($path, 'images/')) {
            return false;
        }

        if (str_ends_with($path, '.webp') && Storage::disk('public')->size($path) < 300 * 1024) {
            return false;
        }

        if (! Storage::disk('public')->exists($path)) {
            $this->warn("Missing file, skipping: {$path}");
            return false;
        }

        $absolutePath = Storage::disk('public')->path($path);
        $newPath = ImageOptimizer::optimizePath($absolutePath, $directory, $maxDimension);

        $save($newPath);
        Storage::disk('public')->delete($path);

        $this->line("{$path} -> {$newPath}");

        return true;
    }
}
