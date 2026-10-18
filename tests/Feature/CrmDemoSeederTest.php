<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Lead;
use App\Models\LeadFollowup;
use App\Models\User;
use App\Models\WhatsAppCs;
use Database\Seeders\CrmDemoSeeder;
use Tests\TestCase;

class CrmDemoSeederTest extends TestCase
{
    public function test_demo_seeder_is_repeatable_and_preserves_existing_leads(): void
    {
        $user = User::factory()->create(['role' => 'cs', 'is_active' => true]);
        $cs = WhatsAppCs::create([
            'name' => 'CS Penguji', 'phone' => '6281311111111', 'weight' => 1,
            'is_active' => true, 'user_id' => $user->id,
        ]);
        $realContact = Contact::create(['name' => 'Kontak asli', 'phone_normalized' => '6281322222222']);
        $realLead = Lead::create([
            'contact_id' => $realContact->id, 'display_name' => 'Kontak asli', 'assigned_cs_id' => $cs->id,
        ]);

        $this->seed(CrmDemoSeeder::class);
        $this->seed(CrmDemoSeeder::class);

        $this->assertDatabaseCount('leads', 8);
        $this->assertDatabaseCount('lead_followups', 3);
        $this->assertSame('Kontak asli', $realLead->fresh()->display_name);
        $this->assertSame(7, Lead::where('display_name', 'like', '[DEMO]%')->count());
        $this->assertDatabaseHas('leads', [
            'display_name' => '[DEMO] Kebutuhan lainnya',
            'city' => 'Parepare',
            'need_type' => 'other',
            'needs_summary' => 'Pendamping lansia dan bantuan kursi roda',
        ]);
        $this->assertNotNull(Lead::where('stage', 'berhasil')->firstOrFail()->won_at);
        $this->assertNotNull(Lead::where('stage', 'gagal')->firstOrFail()->lost_reason);
        $this->assertSame($user->id, LeadFollowup::firstOrFail()->assigned_user_id);
    }
}
