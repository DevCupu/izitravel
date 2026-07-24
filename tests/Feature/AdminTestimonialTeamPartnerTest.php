<?php

namespace Tests\Feature;

use App\Models\Partner;
use App\Models\Team;
use App\Models\Testimonial;
use App\Models\User;
use Tests\TestCase;

class AdminTestimonialTeamPartnerTest extends TestCase
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

    public function test_admin_can_manage_testimonials(): void
    {
        // Index
        $this->actingAs($this->admin)->get(route('admin.testimonials.index'))->assertStatus(200);

        // Store
        $storeRes = $this->actingAs($this->admin)->post(route('admin.testimonials.store'), [
            'name' => 'H. Ahmad Subardjo',
            'location' => 'Jakarta Selatan',
            'message' => 'Pelayanan luar biasa, hotel sangat dekat masjid.',
            'rating' => 5,
            'is_active' => 1,
        ]);
        $storeRes->assertRedirect(route('admin.testimonials.index'));
        $this->assertDatabaseHas('testimonials', ['name' => 'H. Ahmad Subardjo']);
    }

    public function test_admin_can_manage_team_members(): void
    {
        // Index
        $this->actingAs($this->admin)->get(route('admin.teams.index'))->assertStatus(200);

        // Store
        $storeRes = $this->actingAs($this->admin)->post(route('admin.teams.store'), [
            'name' => 'Ustadz Abdullah Lc',
            'role' => 'Pembimbing Ibadah / Muthawwif Senior',
            'description' => 'Lulusan Universitas Islam Madinah berpengalaman 10 tahun.',
            'is_active' => 1,
            'order' => 1,
        ]);
        $storeRes->assertRedirect(route('admin.teams.index'));
        $this->assertDatabaseHas('teams', ['name' => 'Ustadz Abdullah Lc']);
    }

    public function test_admin_can_manage_partners(): void
    {
        // Index
        $this->actingAs($this->admin)->get(route('admin.partners.index'))->assertStatus(200);

        // Store
        $storeRes = $this->actingAs($this->admin)->post(route('admin.partners.store'), [
            'name' => 'Qatar Airways',
            'logo_type' => 'svg',
            'svg_code' => '<svg></svg>',
            'is_active' => 1,
            'order' => 1,
        ]);
        $storeRes->assertRedirect(route('admin.partners.index'));
        $this->assertDatabaseHas('partners', ['name' => 'Qatar Airways']);
    }
}
