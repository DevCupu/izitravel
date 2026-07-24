<?php

namespace Tests\Unit;

use App\Models\Setting;
use Tests\TestCase;

class SettingModelTest extends TestCase
{
    public function test_setting_can_get_and_set_values(): void
    {
        Setting::setValue('test_key', 'test_value');

        $this->assertEquals('test_value', Setting::getValue('test_key'));
    }

    public function test_setting_returns_default_when_key_does_not_exist(): void
    {
        $this->assertEquals('default_fallback', Setting::getValue('non_existent_key', 'default_fallback'));
    }
}
