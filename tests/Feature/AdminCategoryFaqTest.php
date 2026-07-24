<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Faq;
use App\Models\User;
use Tests\TestCase;

class AdminCategoryFaqTest extends TestCase
{
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }

    public function test_admin_can_manage_categories(): void
    {
        // Index
        $indexRes = $this->actingAs($this->admin)->get(route('admin.categories.index'));
        $indexRes->assertStatus(200);

        // Store
        $storeRes = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Umrah Ramadan',
            'is_active' => 1,
        ]);
        $storeRes->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Umrah Ramadan']);

        $category = Category::where('name', 'Umrah Ramadan')->first();

        // Update
        $updateRes = $this->actingAs($this->admin)->put(route('admin.categories.update', $category), [
            'name' => 'Umrah Lailatul Qadr Ramadan',
            'is_active' => 1,
        ]);
        $updateRes->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Umrah Lailatul Qadr Ramadan']);

        // Destroy
        $destroyRes = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $category));
        $destroyRes->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_admin_can_manage_faqs(): void
    {
        // Index
        $indexRes = $this->actingAs($this->admin)->get(route('admin.faqs.index'));
        $indexRes->assertStatus(200);

        // Store
        $storeRes = $this->actingAs($this->admin)->post(route('admin.faqs.store'), [
            'question' => 'Apakah harga sudah termasuk visa umrah?',
            'answer' => 'Ya, seluruh paket yang tercantum sudah termasuk visa umrah resmi.',
            'order' => 1,
            'is_active' => 1,
        ]);
        $storeRes->assertRedirect(route('admin.faqs.index'));
        $this->assertDatabaseHas('faqs', ['question' => 'Apakah harga sudah termasuk visa umrah?']);

        $faq = Faq::where('question', 'Apakah harga sudah termasuk visa umrah?')->first();

        // Update
        $updateRes = $this->actingAs($this->admin)->put(route('admin.faqs.update', $faq), [
            'question' => 'Apakah harga sudah termasuk tiket pesawat & visa?',
            'answer' => 'Ya, harga sudah all-in termasuk tiket pesawat PP dan visa.',
            'order' => 1,
            'is_active' => 1,
        ]);
        $updateRes->assertRedirect(route('admin.faqs.index'));
        $this->assertDatabaseHas('faqs', ['question' => 'Apakah harga sudah termasuk tiket pesawat & visa?']);

        // Destroy
        $destroyRes = $this->actingAs($this->admin)->delete(route('admin.faqs.destroy', $faq));
        $destroyRes->assertRedirect(route('admin.faqs.index'));
        $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);
    }
}
