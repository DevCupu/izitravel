<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Tests\TestCase;

class AdminArticleCrudTest extends TestCase
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

    public function test_admin_can_view_articles_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.articles.index'));

        $response->assertStatus(200);
        $response->assertSee('Artikel');
    }

    public function test_admin_can_create_article(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.articles.store'), [
            'title' => 'Tips Memilih Travel Umrah Resmi',
            'content' => 'Pastikan memilih travel umrah yang terdaftar resmi di Kemenag PPIU.',
            'category' => 'tips',
            'author' => 'Admin IZI Travel',
            'is_published' => 1,
        ]);

        $response->assertRedirect(route('admin.articles.index'));
        $this->assertDatabaseHas('articles', [
            'title' => 'Tips Memilih Travel Umrah Resmi',
        ]);
    }

    public function test_admin_can_update_article(): void
    {
        $article = Article::create([
            'title' => 'Judul Lama Artikel',
            'slug' => 'judul-lama-artikel',
            'content' => 'Konten lama',
            'category' => 'panduan',
            'author' => 'Penulis',
            'is_published' => true,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.articles.update', $article), [
            'title' => 'Judul Baru Artikel Terupdate',
            'content' => 'Konten baru yang disempurnakan',
            'category' => 'panduan',
            'author' => 'Redaksi IZI Travel',
            'is_published' => 1,
        ]);

        $response->assertRedirect(route('admin.articles.index'));
        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => 'Judul Baru Artikel Terupdate',
        ]);
    }

    public function test_admin_can_delete_article(): void
    {
        $article = Article::create([
            'title' => 'Artikel Hendak Dihapus',
            'slug' => 'artikel-hendak-dihapus',
            'content' => 'Konten',
            'category' => 'info',
            'author' => 'Editor',
            'is_published' => false,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.articles.destroy', $article));

        $response->assertRedirect(route('admin.articles.index'));
        $this->assertDatabaseMissing('articles', [
            'id' => $article->id,
        ]);
    }
}
