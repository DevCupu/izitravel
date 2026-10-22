<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\ChatLog;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\LeadStage;
use App\Models\LeadFollowup;
use App\Models\User;
use App\Models\WhatsAppCs;
use Carbon\Carbon;
use Tests\TestCase;

class CrmLeadsTest extends TestCase
{
    private User $admin;
    private User $andi;
    private User $budi;
    private WhatsAppCs $andiCs;
    private WhatsAppCs $budiCs;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
        $this->andi = User::factory()->create(['role' => 'cs', 'email_verified_at' => now()]);
        $this->budi = User::factory()->create(['role' => 'cs', 'email_verified_at' => now()]);
        $this->andiCs = WhatsAppCs::create(['name' => 'Andi', 'phone' => '6281211111111', 'weight' => 1, 'is_active' => true, 'user_id' => $this->andi->id]);
        $this->budiCs = WhatsAppCs::create(['name' => 'Budi', 'phone' => '6281222222222', 'weight' => 1, 'is_active' => true, 'user_id' => $this->budi->id]);
    }

    private function lead(WhatsAppCs $cs, string $phone = '6281312345678'): Lead
    {
        $contact = Contact::firstOrCreate(['phone_normalized' => $phone], ['name' => 'Siti']);
        return Lead::create(['contact_id' => $contact->id, 'display_name' => 'Siti', 'assigned_cs_id' => $cs->id]);
    }

    public function test_admin_can_find_cs_account_setup_from_team_list(): void
    {
        $newCs = WhatsAppCs::create(['name' => 'Citra', 'phone' => '6281233333333', 'weight' => 1, 'is_active' => true]);

        $this->actingAs($this->admin)->get(route('admin.whatsapp-cs.index'))
            ->assertOk()
            ->assertSee('Tim &amp; Akun CS', false)
            ->assertSee('Buat akun login')
            ->assertSee('Belum dibuat')
            ->assertSee('Citra');
        $this->actingAs($this->admin)->get(route('admin.whatsapp-cs.edit', $newCs))
            ->assertOk()
            ->assertSee('Buat akun login CS');
    }

    public function test_cs_can_open_guide_and_admin_cannot(): void
    {
        $this->get(route('cs.guide'))->assertRedirect('/login');
        $this->actingAs($this->andi)->get(route('cs.guide'))
            ->assertOk()
            ->assertSee('Empat langkah kerja CS')
            ->assertSee('Catat calon jemaah')
            ->assertSee('Nomor WhatsApp sudah tercatat?');
        $this->actingAs($this->admin)->get(route('cs.guide'))->assertForbidden();
    }

    public function test_admin_creates_account_with_one_time_password_and_cs_must_change_it(): void
    {
        $newCs = WhatsAppCs::create(['name' => 'Citra', 'phone' => '6281233333333', 'weight' => 1, 'is_active' => true]);
        $response = $this->actingAs($this->admin)->post(route('admin.whatsapp-cs.account.store', $newCs), ['email' => 'citra@example.test']);
        $response->assertRedirect(route('admin.whatsapp-cs.edit', $newCs))->assertSessionHas('temporary_password');
        $temporaryPassword = $response->getSession()->get('temporary_password');
        $newCs->refresh();
        $this->assertTrue($newCs->user->must_change_password);
        $this->actingAs($this->admin)->patch(route('admin.whatsapp-cs.account.email', $newCs), ['email' => 'citra-baru@example.test'])->assertRedirect();
        $this->assertSame('citra-baru@example.test', $newCs->user->fresh()->email);
        $this->actingAs($newCs->user)->get(route('cs.dashboard'))->assertRedirect(route('cs.password.edit'));
        $this->actingAs($newCs->user)->put(route('cs.password.update'), [
            'current_password' => $temporaryPassword,
            'password' => 'NewStrongPassword123!',
            'password_confirmation' => 'NewStrongPassword123!',
        ])->assertRedirect(route('cs.dashboard'))->assertSessionHasNoErrors();
        $this->assertFalse($newCs->user->fresh()->must_change_password);
        $this->actingAs($this->admin)->patch(route('admin.whatsapp-cs.account.status', $newCs), ['is_active' => 0]);
        $this->actingAs($newCs->user->fresh())->get(route('cs.dashboard'))->assertForbidden();
        $this->post(route('logout'));
        $this->post(route('login'), ['email' => 'citra-baru@example.test', 'password' => 'NewStrongPassword123!'])
            ->assertSessionHasErrors('email');
    }

    public function test_cs_can_only_see_and_change_own_leads(): void
    {
        $this->assertSame('cs', $this->andi->role);
        $this->assertTrue($this->andi->is_active);
        $this->assertTrue($this->andi->whatsappCs()->exists());
        $own = $this->lead($this->andiCs);
        $other = $this->lead($this->budiCs, '6281399999999');
        $this->actingAs($this->andi)->get(route('cs.leads.index'))->assertOk()->assertSee('Siti');
        $this->actingAs($this->andi)->get(route('cs.leads.show', $own))->assertOk();
        $this->actingAs($this->andi)->get(route('cs.leads.show', $other))->assertNotFound();
        $this->actingAs($this->andi)->patch(route('cs.leads.stage', $other), ['stage' => 'dihubungi'])->assertNotFound();
        $this->actingAs($this->andi)->get(route('admin.crm.leads.index'))->assertForbidden();
        $this->actingAs($this->admin)->get(route('cs.leads.index'))->assertForbidden();
    }

    public function test_cs_lead_list_shows_own_quick_actions_without_admin_controls(): void
    {
        $own = $this->lead($this->andiCs);
        $this->lead($this->budiCs, '6281399999999');

        $this->actingAs($this->andi)->get(route('cs.leads.index'))
            ->assertOk()
            ->assertSee('Aksi CS')
            ->assertSee('https://wa.me/6281312345678', false)
            ->assertSee('Jadwalkan follow-up Siti')
            ->assertSee('openFollowup('.$own->id)
            ->assertSee(route('cs.leads.show', $own).'#tahap-penjualan', false)
            ->assertDontSee('6281399999999')
            ->assertDontSee('Arsipkan terpilih');

        $this->actingAs($this->admin)->get(route('admin.crm.leads.index'))
            ->assertOk()
            ->assertDontSee('Aksi CS')
            ->assertDontSee('Jadwalkan follow-up Siti');
    }

    public function test_create_lead_normalizes_phone_warns_on_duplicate_and_keeps_tracking_separate(): void
    {
        $payload = ['name' => 'Siti', 'phone' => '0813 1234 5678', 'needs_summary' => 'Dua jemaah'];
        $this->actingAs($this->andi)->get(route('cs.leads.create'))
            ->assertOk()
            ->assertSee('Nama calon jemaah')
            ->assertSee('Tambah paket atau sumber (opsional)')
            ->assertDontSee('name="confirm_duplicate"', false);
        $this->actingAs($this->andi)->post(route('cs.leads.store'), $payload)->assertRedirect();
        $this->assertDatabaseHas('contacts', ['phone_normalized' => '6281312345678']);
        $this->assertDatabaseCount('leads', 1);
        $this->actingAs($this->andi)->post(route('cs.leads.store'), $payload)
            ->assertSessionHas('duplicate_warning')->assertSessionHas('duplicate_leads');
        $this->actingAs($this->andi)->get(route('cs.leads.create'))
            ->assertOk()
            ->assertSee('Nomor WhatsApp ini sudah pernah dicatat')
            ->assertSee('name="confirm_duplicate"', false);
        $this->assertDatabaseCount('leads', 1);
        $this->actingAs($this->andi)->post(route('cs.leads.store'), $payload + ['confirm_duplicate' => 1])->assertRedirect();
        $this->assertDatabaseCount('contacts', 1);
        $this->assertDatabaseCount('leads', 2);
        $this->assertDatabaseCount('chat_logs', 0);
    }

    public function test_intake_fields_and_other_need_are_saved_and_audited(): void
    {
        $payload = [
            'name' => 'Ibu Siti', 'phone' => '0812 3333 4444',
            'city' => 'Makassar', 'planned_departure_month' => '2027-03', 'need_type' => 'other',
        ];
        $this->actingAs($this->andi)->post(route('cs.leads.store'), $payload)
            ->assertSessionHasErrors('needs_summary');
        $this->actingAs($this->andi)->post(route('cs.leads.store'), $payload + ['needs_summary' => 'Pendamping lansia'])
            ->assertRedirect();

        $lead = Lead::firstOrFail();
        $this->assertSame('Makassar', $lead->city);
        $this->assertSame('2027-03', $lead->planned_departure_month);
        $this->assertSame('other', $lead->need_type);
        $this->actingAs($this->andi)->get(route('cs.leads.show', $lead))
            ->assertOk()->assertSee('Maret 2027')->assertSee('Pendamping lansia');

        $this->actingAs($this->andi)->patch(route('cs.leads.update', $lead), [
            'display_name' => 'Ibu Siti', 'city' => 'Gowa',
            'planned_departure_month' => '2027-04', 'need_type' => 'facilities',
        ])->assertRedirect();
        $this->assertSame('Gowa', $lead->fresh()->city);
        $this->assertSame('2027-04', $lead->fresh()->planned_departure_month);
        $this->assertSame('facilities', $lead->fresh()->need_type);
        $this->assertNull($lead->fresh()->needs_summary);
        $this->assertDatabaseHas('lead_activities', ['lead_id' => $lead->id, 'type' => 'updated', 'actor_user_id' => $this->andi->id]);
    }

    public function test_stage_outcome_requires_evidence_and_keeps_an_audit_trail(): void
    {
        $lead = $this->lead($this->andiCs);
        $this->actingAs($this->andi)->patch(route('cs.leads.stage', $lead), ['stage' => 'berhasil'])->assertSessionHasErrors(['confirmation_note', 'confirmed_on']);
        $this->actingAs($this->andi)->patch(route('cs.leads.stage', $lead), ['stage' => 'berhasil', 'confirmation_note' => 'DP diterima', 'confirmed_on' => '2026-10-14'])->assertRedirect();
        $this->assertSame('berhasil', $lead->fresh()->stage);
        $this->assertSame('2026-10-14', $lead->fresh()->won_at->timezone('Asia/Makassar')->toDateString());
        $this->assertDatabaseHas('lead_activities', ['lead_id' => $lead->id, 'type' => 'stage_changed', 'actor_user_id' => $this->andi->id]);
        $this->actingAs($this->andi)->patch(route('cs.leads.stage', $lead), ['stage' => 'gagal', 'lost_reason' => 'Batal'])->assertForbidden();
        $this->actingAs($this->admin)->patch(route('admin.crm.leads.stage', $lead), ['stage' => 'gagal', 'lost_reason' => 'Batal'])->assertRedirect();
        $this->assertSame('gagal', $lead->fresh()->stage);
    }

    public function test_admin_can_change_a_lead_stage_from_the_list_and_reopen_validation_errors(): void
    {
        $lead = $this->lead($this->andiCs);
        $listUrl = route('admin.crm.leads.index');

        $this->actingAs($this->admin)->get($listUrl)
            ->assertOk()
            ->assertSee('Ubah tahap Siti')
            ->assertSee('Ubah tahap lead')
            ->assertSee('Atur tahap')
            ->assertSee('name="stage_lead_id"', false);

        $this->actingAs($this->admin)->from($listUrl)->patch(route('admin.crm.leads.stage', $lead), [
            'stage_lead_id' => $lead->id,
            'stage' => 'gagal',
        ])->assertRedirect($listUrl)->assertSessionHasErrors('lost_reason');

        $this->actingAs($this->admin)->get($listUrl)
            ->assertOk()
            ->assertSee('Alasan gagal')
            ->assertSee('openStage(', false);

        $this->actingAs($this->admin)->from($listUrl)->patch(route('admin.crm.leads.stage', $lead), [
            'stage_lead_id' => $lead->id,
            'stage' => 'gagal',
            'lost_reason' => 'Jadwal tidak sesuai',
        ])->assertRedirect($listUrl)->assertSessionHasNoErrors();

        $this->assertSame('gagal', $lead->fresh()->stage);
        $this->assertDatabaseHas('lead_activities', [
            'lead_id' => $lead->id,
            'actor_user_id' => $this->admin->id,
            'type' => 'stage_changed',
            'note' => 'Jadwal tidak sesuai',
        ]);
        $this->actingAs($this->admin)->get($listUrl)->assertOk()->assertSee('Gagal');
    }

    public function test_followup_is_saved_in_utc_and_transferred_with_pic(): void
    {
        $lead = $this->lead($this->andiCs);
        $this->actingAs($this->andi)->post(route('cs.leads.followups', $lead), ['due_at' => '2026-10-15T00:30', 'description' => 'Hubungi kembali'])->assertRedirect();
        $task = LeadFollowup::firstOrFail();
        $this->assertSame('2026-10-14 16:30:00', $task->due_at->format('Y-m-d H:i:s'));
        $this->actingAs($this->admin)->patch(route('admin.crm.leads.update', $lead), [
            'display_name' => 'Siti', 'assigned_cs_id' => $this->budiCs->id,
        ])->assertRedirect();
        $this->assertSame($this->budi->id, $task->fresh()->assigned_user_id);
        $this->assertDatabaseHas('lead_activities', ['lead_id' => $lead->id, 'type' => 'updated', 'actor_user_id' => $this->admin->id]);
        $this->actingAs($this->andi)->patch(route('cs.followups.complete', $task))->assertNotFound();
        $this->actingAs($this->budi)->patch(route('cs.followups.complete', $task))->assertRedirect();
        $this->assertSame('completed', $task->fresh()->status);
    }

    public function test_manual_source_is_labeled_and_report_renders(): void
    {
        $campaign = Campaign::create(['name' => 'Oktober', 'utm_campaign' => 'oktober', 'is_active' => true]);
        $this->actingAs($this->andi)->post(route('cs.leads.store'), [
            'name' => 'Siti', 'phone' => '081311111111', 'campaign_id' => $campaign->id,
        ])->assertRedirect();
        $this->assertDatabaseHas('leads', ['campaign_id' => $campaign->id, 'attribution_method' => 'manual']);
        $this->actingAs($this->admin)->get(route('admin.crm.report'))->assertOk()->assertSee('Dipilih manual');
    }

    public function test_report_uses_each_event_date_in_wita_across_midnight(): void
    {
        $chat = ChatLog::create([
            'token' => 'report-wita-midnight',
            'user_agent' => 'Mozilla/5.0',
            'clicked_at' => Carbon::parse('2026-10-14 16:10:00', 'UTC'),
        ]);
        $chat->forceFill(['created_at' => Carbon::parse('2026-10-14 15:50:00', 'UTC')])->save();

        $lead = $this->lead($this->andiCs);
        $lead->forceFill([
            'created_at' => Carbon::parse('2026-10-14 15:55:00', 'UTC'),
            'stage' => 'berhasil',
            'won_at' => Carbon::parse('2026-10-14 16:20:00', 'UTC'),
            'confirmation_note' => 'DP diterima',
        ])->save();

        $this->actingAs($this->admin)->get(route('admin.crm.report', ['from' => '2026-10-14', 'to' => '2026-10-14']))
            ->assertOk()
            ->assertViewHas('visits', 1)
            ->assertViewHas('redirects', 0)
            ->assertViewHas('leadCount', 1)
            ->assertViewHas('wonCount', 0)
            ->assertViewHas('trend', fn ($trend) => count($trend) === 1 && $trend[0]['visits'] === 1 && $trend[0]['leads'] === 1);

        $this->actingAs($this->admin)->get(route('admin.crm.report', ['from' => '2026-10-15', 'to' => '2026-10-15']))
            ->assertOk()
            ->assertViewHas('visits', 0)
            ->assertViewHas('redirects', 1)
            ->assertViewHas('leadCount', 0)
            ->assertViewHas('wonCount', 1)
            ->assertViewHas('trend', fn ($trend) => count($trend) === 1 && $trend[0]['redirects'] === 1 && $trend[0]['wins'] === 1);

        $this->actingAs($this->admin)->get(route('admin.crm.report', ['from' => '2026-09-01', 'to' => '2026-10-15']))
            ->assertOk()
            ->assertViewHas('granularity', 'day');
        $this->actingAs($this->admin)->get(route('admin.crm.report', ['from' => '2026-08-01', 'to' => '2026-10-15']))
            ->assertOk()
            ->assertViewHas('granularity', 'week')
            ->assertViewHas('trend', fn ($trend) => collect($trend)->sum('wins') === 1);
        $this->actingAs($this->admin)->get(route('admin.crm.report', ['from' => '2026-01-01', 'to' => '2026-10-15']))
            ->assertOk()
            ->assertViewHas('granularity', 'month')
            ->assertViewHas('trend', fn ($trend) => collect($trend)->sum('redirects') === 1);
    }

    public function test_cs_and_admin_workspaces_render_their_main_pages(): void
    {
        $lead = $this->lead($this->andiCs);
        foreach (['cs.dashboard', 'cs.leads.index', 'cs.leads.create', 'cs.tasks', 'cs.pipeline'] as $name) {
            $this->actingAs($this->andi)->get(route($name))->assertOk();
        }
        $this->actingAs($this->andi)->get(route('cs.leads.show', $lead))->assertOk()->assertSee('Buka WhatsApp');
        foreach (['admin.crm.dashboard', 'admin.crm.leads.index', 'admin.crm.leads.create', 'admin.crm.tasks', 'admin.crm.pipeline', 'admin.crm.report'] as $name) {
            $this->actingAs($this->admin)->get(route($name))->assertOk();
        }
        $this->actingAs($this->admin)->get(route('admin.crm.leads.show', $lead))->assertOk();
    }

    public function test_pipeline_shows_all_stages_with_only_visible_leads_and_outcome_controls(): void
    {
        $own = $this->lead($this->andiCs);
        $own->update(['stage' => 'berhasil', 'confirmation_note' => 'DP diterima', 'won_at' => now()]);
        $other = $this->lead($this->budiCs, '6281399999999');
        $other->update(['stage' => 'gagal', 'lost_reason' => 'Ditunda']);

        $this->actingAs($this->andi)->get(route('cs.pipeline'))->assertOk()
            ->assertViewHas('counts', fn ($counts) => (int) $counts['berhasil'] === 1 && ! isset($counts['gagal']))
            ->assertSee('Tahap Berhasil')->assertSee('Tahap Gagal')
            ->assertSee('aria-label="Tampilan pipeline"', false)
            ->assertSee('aria-label="Tabel lead tahap Berhasil"', false)
            ->assertSee('DP diterima')->assertDontSee('Ditunda')
            ->assertSee('Catatan konfirmasi')->assertSee('Alasan tidak berlanjut');

        $this->actingAs($this->admin)->get(route('admin.crm.pipeline'))->assertOk()
            ->assertViewHas('counts', fn ($counts) => (int) $counts['berhasil'] === 1 && (int) $counts['gagal'] === 1);
    }

    public function test_cs_pipeline_bulk_move_is_limited_to_owned_leads_and_open_stages(): void
    {
        $first = $this->lead($this->andiCs);
        $second = $this->lead($this->andiCs, '6281312345679');
        $other = $this->lead($this->budiCs, '6281399999999');
        $payload = ['action' => 'move', 'source_stage' => 'baru', 'target_stage' => 'dihubungi'];

        $this->actingAs($this->andi)->get(route('cs.pipeline'))->assertOk()
            ->assertSee('Aksi massal pipeline')->assertSee('Pilih semua yang tampil')
            ->assertDontSee('Ganti PIC')->assertDontSee('Arsipkan lead');
        $this->actingAs($this->andi)->post(route('cs.pipeline.bulk'), $payload + ['ids' => [$first->id, $other->id]])->assertNotFound();
        $this->assertSame('baru', $first->fresh()->stage);
        $this->actingAs($this->andi)->post(route('cs.pipeline.bulk'), ['action' => 'archive', 'source_stage' => 'baru', 'ids' => [$first->id]])->assertForbidden();
        $this->actingAs($this->andi)->post(route('cs.pipeline.bulk'), ['action' => 'assign', 'source_stage' => 'baru', 'assigned_cs_id' => $this->budiCs->id, 'ids' => [$first->id]])->assertForbidden();
        $this->actingAs($this->andi)->post(route('cs.pipeline.bulk'), array_replace($payload, ['target_stage' => 'berhasil', 'ids' => [$first->id]]))->assertSessionHasErrors('target_stage');
        $this->actingAs($this->andi)->post(route('cs.pipeline.bulk'), array_replace($payload, ['source_stage' => 'konsultasi', 'ids' => [$first->id]]))->assertStatus(409);

        $this->actingAs($this->andi)->post(route('cs.pipeline.bulk'), $payload + ['ids' => [$first->id, $second->id]])->assertRedirect();
        $this->assertSame('dihubungi', $first->fresh()->stage);
        $this->assertSame('dihubungi', $second->fresh()->stage);
        $this->assertNotNull($first->fresh()->first_contacted_at);
        $this->assertDatabaseHas('lead_activities', ['lead_id' => $first->id, 'type' => 'stage_changed', 'actor_user_id' => $this->andi->id]);

        $won = $this->lead($this->andiCs, '6281312345680');
        $won->update(['stage' => 'berhasil', 'won_at' => now(), 'confirmation_note' => 'DP diterima']);
        $this->actingAs($this->andi)->post(route('cs.pipeline.bulk'), array_replace($payload, ['source_stage' => 'berhasil', 'ids' => [$won->id]]))->assertForbidden();
    }

    public function test_admin_pipeline_bulk_assign_and_archive_are_atomic(): void
    {
        $first = $this->lead($this->andiCs);
        $second = $this->lead($this->andiCs, '6281312345679');
        $task = $first->followups()->create(['assigned_user_id' => $this->andi->id, 'due_at' => now()->addDay(), 'description' => 'Hubungi kembali']);

        $this->actingAs($this->admin)->get(route('admin.crm.pipeline'))->assertOk()
            ->assertSee('Ganti PIC')->assertSee('Arsipkan lead');
        $this->actingAs($this->admin)->post(route('admin.crm.pipeline.bulk'), [
            'action' => 'assign', 'source_stage' => 'baru', 'assigned_cs_id' => $this->budiCs->id,
            'ids' => [$first->id, $second->id],
        ])->assertRedirect();
        $this->assertSame($this->budiCs->id, $first->fresh()->assigned_cs_id);
        $this->assertSame($this->budiCs->id, $second->fresh()->assigned_cs_id);
        $this->assertSame($this->budi->id, $task->fresh()->assigned_user_id);
        $this->assertDatabaseHas('lead_activities', ['lead_id' => $first->id, 'type' => 'updated', 'actor_user_id' => $this->admin->id]);

        $archive = ['action' => 'archive', 'source_stage' => 'baru'];
        $this->actingAs($this->admin)->post(route('admin.crm.pipeline.bulk'), $archive + ['ids' => [$first->id, 999999]])->assertNotFound();
        $this->assertDatabaseCount('leads', 2);
        $this->actingAs($this->admin)->post(route('admin.crm.pipeline.bulk'), $archive + ['ids' => [$first->id, $second->id]])->assertRedirect();
        $this->assertSame(0, Lead::count());
        $this->assertDatabaseHas('lead_activities', ['lead_id' => $first->id, 'type' => 'lead_archived']);
    }

    public function test_admin_pipeline_bulk_reopens_confirmed_lead_and_clears_outcome_fields(): void
    {
        $lead = $this->lead($this->andiCs);
        $lead->update(['stage' => 'berhasil', 'won_at' => now(), 'confirmation_note' => 'DP diterima', 'booking_reference' => 'BK-123']);

        $this->actingAs($this->admin)->post(route('admin.crm.pipeline.bulk'), [
            'action' => 'move', 'source_stage' => 'berhasil', 'target_stage' => 'konsultasi', 'ids' => [$lead->id],
        ])->assertRedirect();

        $lead->refresh();
        $this->assertSame('konsultasi', $lead->stage);
        $this->assertNull($lead->won_at);
        $this->assertNull($lead->confirmation_note);
        $this->assertNull($lead->booking_reference);
        $this->assertSame('berhasil', $lead->activities()->firstOrFail()->changes['stage']['from']);
    }

    public function test_admin_can_configure_stages_and_cs_uses_the_new_order_and_label(): void
    {
        $this->actingAs($this->andi)->get(route('admin.crm.stages.index'))->assertForbidden();
        $this->actingAs($this->andi)->post(route('admin.crm.stages.store'), ['label' => 'Dokumen', 'color' => '#4269ab'])->assertForbidden();

        $this->actingAs($this->admin)->post(route('admin.crm.stages.store'), ['label' => 'Dokumen', 'color' => '#4269ab'])->assertRedirect();
        $custom = LeadStage::where('label', 'Dokumen')->firstOrFail();
        $this->actingAs($this->admin)->patch(route('admin.crm.stages.update', $custom->key), ['label' => 'Verifikasi dokumen', 'color' => '#254a84'])->assertRedirect();
        $this->actingAs($this->admin)->patch(route('admin.crm.stages.move', $custom->key), ['direction' => 'up'])->assertRedirect();
        $this->assertSame(5, $custom->fresh()->position);

        $lead = $this->lead($this->andiCs);
        $this->actingAs($this->andi)->patch(route('cs.leads.stage', $lead), ['stage' => $custom->key])->assertRedirect();
        $this->assertSame($custom->key, $lead->fresh()->stage);
        $this->assertSame('Verifikasi dokumen', $lead->fresh()->activities()->firstOrFail()->changes['stage_labels']['to']);
        $this->actingAs($this->andi)->get(route('cs.pipeline'))->assertOk()->assertSee('Verifikasi dokumen');
        $this->actingAs($this->admin)->get(route('admin.crm.report'))->assertOk()->assertSee('Verifikasi dokumen');
    }

    public function test_deleting_stage_requires_replacement_and_preserves_active_and_archived_leads(): void
    {
        $stage = LeadStage::create(['key' => 't_testing', 'label' => 'Tunggu dokumen', 'kind' => 'open', 'position' => 8, 'color' => '#4269ab']);
        $active = $this->lead($this->andiCs);
        $archived = $this->lead($this->andiCs, '6281399999999');
        $active->update(['stage' => $stage->key]);
        $archived->update(['stage' => $stage->key]);
        $archived->delete();

        $this->actingAs($this->admin)->delete(route('admin.crm.stages.destroy', $stage->key))->assertSessionHasErrors('replacement_stage');
        $this->assertDatabaseHas('lead_stages', ['key' => $stage->key]);
        $this->assertSame($stage->key, $active->fresh()->stage);
        $this->actingAs($this->admin)->delete(route('admin.crm.stages.destroy', $stage->key), ['replacement_stage' => 'berhasil'])->assertSessionHasErrors('replacement_stage');
        $this->actingAs($this->admin)->delete(route('admin.crm.stages.destroy', $stage->key), ['replacement_stage' => 'konsultasi'])->assertRedirect();
        $this->assertDatabaseMissing('lead_stages', ['key' => $stage->key]);
        $this->assertSame('konsultasi', $active->fresh()->stage);
        $this->assertSame('konsultasi', Lead::withTrashed()->findOrFail($archived->id)->stage);
        $this->assertSame('Tunggu dokumen', $active->fresh()->activities()->firstOrFail()->changes['stage_labels']['from']);
        $this->actingAs($this->admin)->delete(route('admin.crm.stages.destroy', 'berhasil'))->assertForbidden();
    }

    public function test_pipeline_quick_edit_preserves_other_fields_and_respects_lead_ownership(): void
    {
        $own = $this->lead($this->andiCs);
        $other = $this->lead($this->budiCs, '6281399999999');
        $own->update(['needs_summary' => 'Butuh kursi roda', 'need_type' => 'other']);

        $this->actingAs($this->andi)->patch(route('cs.leads.pipeline-details', $other), ['display_name' => 'Tidak sah'])->assertNotFound();
        $this->actingAs($this->andi)->patch(route('cs.leads.pipeline-details', $own), ['display_name' => 'Siti Aminah', 'city' => 'Makassar', 'assigned_cs_id' => $this->budiCs->id])->assertSessionHasErrors('assigned_cs_id');
        $this->actingAs($this->andi)->patch(route('cs.leads.pipeline-details', $own), ['display_name' => 'Siti Aminah', 'city' => 'Makassar'])->assertRedirect();
        $this->assertSame('Siti Aminah', $own->fresh()->display_name);
        $this->assertSame('Butuh kursi roda', $own->fresh()->needs_summary);

        $this->actingAs($this->admin)->patch(route('admin.crm.leads.pipeline-details', $own), ['display_name' => 'Siti Aminah', 'city' => 'Gowa', 'assigned_cs_id' => $this->budiCs->id])->assertRedirect();
        $this->assertSame($this->budiCs->id, $own->fresh()->assigned_cs_id);
        $this->assertSame('Gowa', $own->fresh()->city);
    }

    public function test_large_pipeline_limits_each_column_and_admin_can_filter_by_pic(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            $contact = Contact::create(['name' => "Prospek {$i}", 'phone_normalized' => sprintf('628140000%04d', $i)]);
            Lead::create(['contact_id' => $contact->id, 'display_name' => "Prospek {$i}", 'assigned_cs_id' => $this->andiCs->id]);
        }
        $other = $this->lead($this->budiCs, '6281399999999');

        $this->actingAs($this->andi)->get(route('cs.pipeline'))->assertOk()
            ->assertViewHas('counts', fn ($counts) => (int) $counts['baru'] === 15)
            ->assertViewHas('leads', fn ($byStage) => $byStage['baru']->count() === 10)
            ->assertSee('10 terbaru ditampilkan')
            ->assertSee('Lihat semua 15 lead');

        $this->actingAs($this->admin)->get(route('admin.crm.pipeline', ['cs_id' => $this->andiCs->id]))->assertOk()
            ->assertViewHas('selectedCsId', $this->andiCs->id)
            ->assertViewHas('counts', fn ($counts) => (int) $counts['baru'] === 15)
            ->assertSee('cs_id='.$this->andiCs->id)
            ->assertDontSee('data-lead-id="'.$other->id.'"', false);

        $this->actingAs($this->admin)->get(route('admin.crm.pipeline', ['cs_id' => 'invalid']))->assertOk()
            ->assertViewHas('selectedCsId', null)
            ->assertViewHas('counts', fn ($counts) => (int) $counts['baru'] === 16);
    }

    public function test_dashboard_splits_overdue_and_upcoming_tasks_across_wita_midnight(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-14 16:30:00', 'UTC'));
        try {
            $lead = $this->lead($this->andiCs);
            $late = $lead->followups()->create(['assigned_user_id' => $this->andi->id, 'due_at' => Carbon::parse('2026-10-14 16:15:00', 'UTC'), 'description' => 'Terlambat malam']);
            $next = $lead->followups()->create(['assigned_user_id' => $this->andi->id, 'due_at' => Carbon::parse('2026-10-14 17:00:00', 'UTC'), 'description' => 'Hari ini WITA']);
            $this->actingAs($this->andi)->get(route('cs.dashboard'))->assertOk()
                ->assertViewHas('overdueCount', 1)
                ->assertViewHas('dueTodayCount', 1)
                ->assertSee('Prioritas hari ini')
                ->assertViewHas('overdue', fn ($items) => $items->pluck('id')->contains($late->id))
                ->assertViewHas('dueToday', fn ($items) => $items->pluck('id')->contains($next->id));
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_task_period_filters_use_wita_day_and_do_not_repeat_overdue_tasks(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-14 16:30:00', 'UTC'));
        try {
            $lead = $this->lead($this->andiCs);
            $late = $lead->followups()->create(['assigned_user_id' => $this->andi->id, 'due_at' => Carbon::parse('2026-10-14 16:15:00', 'UTC'), 'description' => 'Sudah lewat']);
            $today = $lead->followups()->create(['assigned_user_id' => $this->andi->id, 'due_at' => Carbon::parse('2026-10-14 17:00:00', 'UTC'), 'description' => 'Hari WITA ini']);
            $upcoming = $lead->followups()->create(['assigned_user_id' => $this->andi->id, 'due_at' => Carbon::parse('2026-10-15 16:00:00', 'UTC'), 'description' => 'Hari berikutnya']);

            $this->actingAs($this->andi)->get(route('cs.tasks', ['period' => 'overdue']))->assertOk()
                ->assertViewHas('tasks', fn ($tasks) => $tasks->pluck('id')->all() === [$late->id]);
            $this->get(route('cs.tasks', ['period' => 'today']))->assertOk()
                ->assertViewHas('tasks', fn ($tasks) => $tasks->pluck('id')->all() === [$today->id]);
            $this->get(route('cs.tasks', ['period' => 'upcoming']))->assertOk()
                ->assertViewHas('tasks', fn ($tasks) => $tasks->pluck('id')->all() === [$upcoming->id]);
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_cs_edits_own_open_task_in_wita_and_admin_can_restore_its_archive(): void
    {
        $lead = $this->lead($this->andiCs);
        $task = $lead->followups()->create(['assigned_user_id' => $this->andi->id, 'due_at' => now(), 'description' => 'Hubungi']);
        $other = $this->lead($this->budiCs, '6281399999999')->followups()->create(['assigned_user_id' => $this->budi->id, 'due_at' => now(), 'description' => 'Tugas Budi']);

        $this->actingAs($this->andi)->patch(route('cs.followups.update', $other), ['due_at' => '2026-10-16T10:30', 'description' => 'Ubah'])->assertNotFound();
        $this->actingAs($this->andi)->delete(route('cs.followups.archive', $other))->assertNotFound();
        $this->actingAs($this->andi)->patch(route('cs.followups.update', $task), ['due_at' => '2026-10-16T10:30', 'description' => 'Kirim rincian'])->assertRedirect();
        $this->assertSame('2026-10-16 02:30:00', $task->fresh()->due_at->utc()->format('Y-m-d H:i:s'));
        $this->assertSame('Kirim rincian', $task->fresh()->description);
        $this->assertDatabaseHas('lead_activities', ['lead_id' => $lead->id, 'type' => 'followup_updated']);

        $this->actingAs($this->andi)->delete(route('cs.followups.archive', $task))->assertRedirect();
        $this->assertSoftDeleted($task);
        $this->actingAs($this->andi)->get(route('cs.tasks'))->assertDontSee('Kirim rincian');
        $this->actingAs($this->admin)->get(route('admin.crm.tasks.archived'))->assertOk()->assertSee('Kirim rincian');
        $this->actingAs($this->admin)->patch(route('admin.crm.followups.restore', $task))->assertRedirect();
        $this->assertNull($task->fresh()->deleted_at);
        $this->assertDatabaseHas('lead_activities', ['lead_id' => $lead->id, 'type' => 'followup_restored']);
    }

    public function test_bulk_task_actions_are_atomic_and_completed_tasks_cannot_be_changed(): void
    {
        $lead = $this->lead($this->andiCs);
        $first = $lead->followups()->create(['assigned_user_id' => $this->andi->id, 'due_at' => now(), 'description' => 'Pertama']);
        $second = $lead->followups()->create(['assigned_user_id' => $this->andi->id, 'due_at' => now(), 'description' => 'Kedua']);
        $other = $this->lead($this->budiCs, '6281399999999')->followups()->create(['assigned_user_id' => $this->budi->id, 'due_at' => now(), 'description' => 'Milik Budi']);

        $this->actingAs($this->andi)->post(route('cs.tasks.bulk-complete'), ['ids' => [$first->id, $other->id]])->assertNotFound();
        $this->assertSame('open', $first->fresh()->status);
        $this->actingAs($this->andi)->post(route('cs.tasks.bulk-archive'), ['ids' => [$first->id, 999999]])->assertNotFound();
        $this->assertNull($first->fresh()->deleted_at);

        $this->actingAs($this->andi)->post(route('cs.tasks.bulk-complete'), ['ids' => [$first->id]])->assertRedirect();
        $this->assertSame('completed', $first->fresh()->status);
        $this->actingAs($this->andi)->patch(route('cs.followups.update', $first), ['due_at' => '2026-10-16T10:30', 'description' => 'Tidak boleh'])->assertStatus(409);
        $this->actingAs($this->andi)->delete(route('cs.followups.archive', $first))->assertStatus(409);
        $this->actingAs($this->andi)->post(route('cs.tasks.bulk-archive'), ['ids' => [$first->id, $second->id]])->assertNotFound();
        $this->assertNull($second->fresh()->deleted_at);

        $this->actingAs($this->andi)->post(route('cs.tasks.bulk-archive'), ['ids' => [$second->id]])->assertRedirect();
        $this->assertSoftDeleted($second);
        $this->actingAs($this->admin)->post(route('admin.crm.tasks.bulk-restore'), ['ids' => [$second->id, 999999]])->assertNotFound();
        $this->assertSoftDeleted($second);
        $this->actingAs($this->andi)->post(route('admin.crm.tasks.bulk-restore'), ['ids' => [$second->id]])->assertForbidden();
        $this->actingAs($this->admin)->post(route('admin.crm.tasks.bulk-restore'), ['ids' => [$second->id]])->assertRedirect();
        $this->assertNull($second->fresh()->deleted_at);
    }

    public function test_only_admin_archives_leads_and_restore_returns_history_and_metrics(): void
    {
        $lead = $this->lead($this->andiCs);
        $task = $lead->followups()->create(['assigned_user_id' => $this->andi->id, 'due_at' => now()->subDay(), 'description' => 'Masih perlu dihubungi']);
        $this->actingAs($this->andi)->delete(route('admin.crm.leads.archive', $lead))->assertForbidden();
        $this->actingAs($this->andi)->get(route('admin.crm.leads.archived'))->assertForbidden();
        $this->actingAs($this->admin)->delete(route('admin.crm.leads.archive', $lead))->assertRedirect();
        $this->assertSoftDeleted($lead);
        $this->assertNull($task->fresh()->deleted_at);
        $this->actingAs($this->andi)->get(route('cs.leads.show', $lead))->assertNotFound();
        $this->actingAs($this->andi)->get(route('cs.dashboard'))->assertViewHas('newCount', 0)->assertViewHas('overdueCount', 0);
        $this->actingAs($this->admin)->get(route('admin.crm.pipeline'))->assertViewHas('counts', fn ($counts) => ! isset($counts['baru']));
        $this->actingAs($this->admin)->get(route('admin.crm.report'))->assertViewHas('leadCount', 0);
        $this->actingAs($this->admin)->get(route('admin.crm.leads.archived'))->assertOk()->assertSee('Siti');

        $this->actingAs($this->admin)->patch(route('admin.crm.leads.restore', $lead))->assertRedirect();
        $this->assertNull($lead->fresh()->deleted_at);
        $this->actingAs($this->andi)->get(route('cs.dashboard'))->assertViewHas('newCount', 1)->assertViewHas('overdueCount', 1);
        $this->actingAs($this->admin)->get(route('admin.crm.report'))->assertViewHas('leadCount', 1);
        $this->assertDatabaseHas('lead_activities', ['lead_id' => $lead->id, 'type' => 'lead_archived']);
        $this->assertDatabaseHas('lead_activities', ['lead_id' => $lead->id, 'type' => 'lead_restored']);
    }

    public function test_admin_bulk_lead_archive_and_restore_validate_entire_selection(): void
    {
        $first = $this->lead($this->andiCs);
        $second = $this->lead($this->budiCs, '6281399999999');
        $this->actingAs($this->admin)->post(route('admin.crm.leads.bulk-archive'), ['ids' => [$first->id, 999999]])->assertNotFound();
        $this->assertNull($first->fresh()->deleted_at);
        $this->actingAs($this->admin)->get(route('admin.crm.leads.index'))->assertOk()->assertSee('Pilih semua di halaman ini');
        $this->actingAs($this->admin)->post(route('admin.crm.leads.bulk-archive'), ['ids' => [$first->id, $second->id]])->assertRedirect();
        $this->assertSoftDeleted($first);
        $this->assertSoftDeleted($second);
        $this->actingAs($this->admin)->post(route('admin.crm.leads.bulk-restore'), ['ids' => [$first->id, 999999]])->assertNotFound();
        $this->assertSoftDeleted($first);
        $this->actingAs($this->admin)->post(route('admin.crm.leads.bulk-restore'), ['ids' => [$first->id, $second->id]])->assertRedirect();
        $this->assertNull($first->fresh()->deleted_at);
        $this->assertNull($second->fresh()->deleted_at);
    }

    public function test_restoring_a_lead_does_not_restore_tasks_archived_separately(): void
    {
        $lead = $this->lead($this->andiCs);
        $task = $lead->followups()->create(['assigned_user_id' => $this->andi->id, 'due_at' => now(), 'description' => 'Arsip terpisah']);
        $this->actingAs($this->andi)->delete(route('cs.followups.archive', $task))->assertRedirect();
        $this->actingAs($this->admin)->delete(route('admin.crm.leads.archive', $lead))->assertRedirect();
        $this->actingAs($this->admin)->get(route('admin.crm.tasks.archived'))->assertDontSee('Arsip terpisah');
        $this->actingAs($this->admin)->patch(route('admin.crm.leads.restore', $lead))->assertRedirect();
        $this->assertSoftDeleted($task);
        $this->actingAs($this->admin)->get(route('admin.crm.tasks.archived'))->assertSee('Arsip terpisah');
    }
}
