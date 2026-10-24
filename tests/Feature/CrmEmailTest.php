<?php

namespace Tests\Feature;

use App\Mail\FollowupDueMail;
use App\Models\Contact;
use App\Models\InboundEmail;
use App\Models\InboundMailbox;
use App\Models\Lead;
use App\Models\LeadFollowup;
use App\Models\User;
use App\Models\WhatsAppCs;
use App\Services\InboxSyncService;
use App\Services\MailboxReader;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Mockery;
use Tests\TestCase;

class CrmEmailTest extends TestCase
{
    private User $admin;
    private User $cs;
    private User $otherCs;
    private WhatsAppCs $csProfile;
    private Lead $lead;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
        $this->cs = User::factory()->create(['role' => 'cs', 'email_verified_at' => now()]);
        $this->otherCs = User::factory()->create(['role' => 'cs', 'email_verified_at' => now()]);
        $this->csProfile = WhatsAppCs::create(['name' => 'Andi', 'phone' => '6281211111111', 'is_active' => true, 'weight' => 1, 'user_id' => $this->cs->id]);
        WhatsAppCs::create(['name' => 'Budi', 'phone' => '6281222222222', 'is_active' => true, 'weight' => 1, 'user_id' => $this->otherCs->id]);
        $contact = Contact::create(['name' => 'Siti', 'phone_normalized' => '6281312345678']);
        $this->lead = Lead::create(['contact_id' => $contact->id, 'display_name' => 'Siti', 'assigned_cs_id' => $this->csProfile->id, 'stage' => 'baru']);
    }

    private function email(string $status = 'new'): InboundEmail
    {
        $mailbox = InboundMailbox::firstOrCreate(['account' => 'default'], ['uid_validity' => 11, 'last_uid' => 2]);

        return InboundEmail::create([
            'mailbox_id' => $mailbox->id, 'uid_validity' => 11, 'uid' => random_int(100, 1000000),
            'from_email' => 'siti@example.test', 'from_name' => 'Siti', 'subject' => 'Paket umrah',
            'body_text' => 'Mohon info paket dan jadwal.', 'received_at' => now(), 'status' => $status,
        ]);
    }

    public function test_due_followup_sends_once_and_reschedule_or_reassignment_sends_again(): void
    {
        Mail::fake();
        $this->travelTo(Carbon::parse('2026-10-25 02:30:00', 'UTC'));
        $task = LeadFollowup::create([
            'lead_id' => $this->lead->id, 'assigned_user_id' => $this->cs->id,
            'due_at' => now()->subMinute(), 'description' => 'Hubungi Siti',
        ]);

        Artisan::call('crm:send-followup-reminders');
        Artisan::call('crm:send-followup-reminders');
        Mail::assertSent(FollowupDueMail::class, 1);
        Mail::assertSent(FollowupDueMail::class, function (FollowupDueMail $mail) {
            $this->assertStringContainsString('Hubungi Siti', $mail->render());
            return true;
        });
        $this->assertNotNull($task->fresh()->reminder_sent_at);

        $task->update(['due_at' => now()->addMinute()]);
        $this->assertNull($task->fresh()->reminder_sent_at);
        Artisan::call('crm:send-followup-reminders');
        Mail::assertSent(FollowupDueMail::class, 1);
        $this->travel(2)->minutes();
        Artisan::call('crm:send-followup-reminders');
        Mail::assertSent(FollowupDueMail::class, 2);

        $otherProfile = $this->otherCs->whatsappCs;
        $this->lead->update(['assigned_cs_id' => $otherProfile->id]);
        $task->update(['assigned_user_id' => $this->otherCs->id]);
        Artisan::call('crm:send-followup-reminders');
        Mail::assertSent(FollowupDueMail::class, 3);
        Mail::assertSent(FollowupDueMail::class, fn ($mail) => $mail->hasTo($this->otherCs->email));
    }

    public function test_closed_or_completed_tasks_are_not_emailed(): void
    {
        $this->travelTo(Carbon::parse('2026-10-25 02:30:00', 'UTC'));
        $completed = LeadFollowup::create(['lead_id' => $this->lead->id, 'assigned_user_id' => $this->cs->id, 'due_at' => now()->subMinute(), 'description' => 'Selesai', 'status' => 'completed']);
        Mail::fake();
        Artisan::call('crm:send-followup-reminders');
        Mail::assertNothingSent();
        $this->assertNull($completed->fresh()->reminder_sent_at);

        $this->lead->update(['stage' => 'berhasil']);
        LeadFollowup::create(['lead_id' => $this->lead->id, 'assigned_user_id' => $this->cs->id, 'due_at' => now()->subMinute(), 'description' => 'Lead ditutup']);
        Artisan::call('crm:send-followup-reminders');
        Mail::assertNothingSent();
    }

    public function test_smtp_failure_releases_claim_for_retry(): void
    {
        $this->travelTo(Carbon::parse('2026-10-25 02:30:00', 'UTC'));
        $task = LeadFollowup::create(['lead_id' => $this->lead->id, 'assigned_user_id' => $this->cs->id, 'due_at' => now()->subMinute(), 'description' => 'Hubungi']);
        Mail::shouldReceive('to')->once()->andThrow(new \RuntimeException('SMTP unavailable'));
        $this->assertSame(1, Artisan::call('crm:send-followup-reminders'));
        $this->assertNull($task->fresh()->reminder_sent_at);
        $this->assertNull($task->fresh()->reminder_claimed_at);
    }

    public function test_inbox_starts_after_existing_mail_and_deduplicates_imports(): void
    {
        $reader = Mockery::mock(MailboxReader::class);
        $reader->shouldReceive('status')->times(3)->andReturn(['uid_validity' => 42, 'uid_next' => 8]);
        $reader->shouldReceive('messagesAfter')->once()->with(7)->andReturn([[
            'uid' => 8, 'message_id' => '<m8@example.test>', 'from_email' => 'siti@example.test',
            'from_name' => 'Siti', 'subject' => 'Halo', 'body_text' => 'Pesan baru', 'received_at' => now(),
        ]]);
        $reader->shouldReceive('messagesAfter')->once()->with(8)->andReturn([[
            'uid' => 8, 'message_id' => '<m8@example.test>', 'from_email' => 'siti@example.test',
            'from_name' => 'Siti', 'subject' => 'Halo', 'body_text' => 'Pesan baru', 'received_at' => now(),
        ]]);
        $service = new InboxSyncService($reader);

        $this->assertSame(0, $service->sync());
        $this->assertSame(7, InboundMailbox::first()->last_uid);
        $this->assertSame(1, $service->sync());
        $this->assertSame(0, $service->sync());
        $this->assertDatabaseCount('inbound_emails', 1);
        $this->assertNull(InboundMailbox::first()->last_error);
    }

    public function test_imap_failure_is_visible_to_admin(): void
    {
        $reader = Mockery::mock(MailboxReader::class);
        $reader->shouldReceive('status')->once()->andThrow(new \RuntimeException('IMAP unavailable'));
        $this->expectException(\RuntimeException::class);
        try {
            (new InboxSyncService($reader))->sync();
        } finally {
            $this->assertSame('IMAP unavailable', InboundMailbox::first()->last_error);
            $this->actingAs($this->admin)->get(route('admin.crm.inbox.index'))->assertOk()->assertSee('IMAP unavailable');
        }
    }

    public function test_admin_can_link_email_and_only_owner_cs_sees_it(): void
    {
        $email = $this->email();
        $this->actingAs($this->otherCs)->get(route('admin.crm.inbox.show', $email))->assertForbidden();
        $this->actingAs($this->admin)->get(route('admin.crm.inbox.show', $email))->assertOk()->assertSee('Mohon info paket');
        $this->actingAs($this->admin)->post(route('admin.crm.inbox.link', $email), ['lead_id' => $this->lead->id])->assertRedirect();
        $this->assertSame($this->lead->id, $email->fresh()->lead_id);
        $this->actingAs($this->cs)->get(route('cs.leads.show', $this->lead))->assertOk()->assertSee('Mohon info paket');
        $this->actingAs($this->otherCs)->get(route('cs.leads.show', $this->lead))->assertNotFound();

        $otherLead = Lead::create(['contact_id' => $this->lead->contact_id, 'display_name' => 'Siti lain', 'assigned_cs_id' => $this->otherCs->whatsappCs->id, 'stage' => 'baru']);
        $this->actingAs($this->admin)->post(route('admin.crm.inbox.link', $email), ['lead_id' => $otherLead->id])->assertRedirect();
        $this->actingAs($this->cs)->get(route('cs.leads.show', $this->lead))->assertDontSee('Mohon info paket');
        $this->actingAs($this->otherCs)->get(route('cs.leads.show', $otherLead))->assertOk()->assertSee('Mohon info paket');

        $this->actingAs($this->admin)->post(route('admin.crm.inbox.ignore', $email))->assertRedirect();
        $this->assertNull($email->fresh()->lead_id);
        $this->actingAs($this->otherCs)->get(route('cs.leads.show', $otherLead))->assertDontSee('Mohon info paket');
    }

    public function test_admin_can_create_lead_from_email_with_required_phone_and_cs(): void
    {
        $email = $this->email();
        $this->actingAs($this->admin)->get(route('admin.crm.leads.create', ['inbound_email' => $email->id]))
            ->assertOk()->assertSee('siti@example.test');
        $this->actingAs($this->admin)->post(route('admin.crm.leads.store'), [
            'name' => 'Siti Baru', 'phone' => '081399999999',
            'assigned_cs_id' => $this->csProfile->id, 'inbound_email_id' => $email->id,
        ])->assertRedirect();
        $this->assertSame('linked', $email->fresh()->status);
        $this->assertSame('Siti Baru', $email->fresh()->lead->display_name);
    }
}
