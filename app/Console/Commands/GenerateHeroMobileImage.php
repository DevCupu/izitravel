<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Support\ImageOptimizer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateHeroMobileImage extends Command
{
    protected $signature = 'images:generate-hero-mobile';

    protected $description = 'Generate a small hero background for mobile from the current admin image';

    public function handle(): int
    {
        $source = Setting::getValue('hero_image');
        if (! $source || str_starts_with($source, 'images/')) {
            $this->info('Bundled hero image already has a mobile version.');

            return self::SUCCESS;
        }

        $disk = Storage::disk('public');
        if (! $disk->exists($source)) {
            $this->warn("Hero image is missing: {$source}");

            return self::FAILURE;
        }

        $previous = Setting::getValue('hero_image_mobile');
        if (Setting::getValue('hero_image_mobile_source') === $source && $previous && $disk->exists($previous)) {
            $this->info('Mobile hero image is up to date.');

            return self::SUCCESS;
        }

        $mobile = ImageOptimizer::optimizePath($disk->path($source), 'settings', 800, 'public', 70);
        Setting::setValue('hero_image_mobile', $mobile);
        Setting::setValue('hero_image_mobile_source', $source);

        if ($previous && $previous !== $source && ! str_starts_with($previous, 'images/')) {
            $disk->delete($previous);
        }

        $this->info("Generated mobile hero image: {$mobile}");

        return self::SUCCESS;
    }
}
