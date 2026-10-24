<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InboundEmail;
use App\Models\InboundMailbox;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CrmInboxController extends Controller
{
    public function index(Request $request): View
    {
        $data = $request->validate(['status' => ['nullable', Rule::in(['new', 'linked', 'ignored'])]]);
        $status = $data['status'] ?? 'new';
        $emails = InboundEmail::query()->with('lead')->where('status', $status)
            ->orderByDesc('received_at')->orderByDesc('id')->paginate(20)->withQueryString();
        $mailbox = InboundMailbox::where('account', 'default')->first();

        return view('admin.crm-inbox.index', compact('emails', 'mailbox', 'status'));
    }

    public function show(Request $request, InboundEmail $email): View
    {
        $data = $request->validate(['q' => ['nullable', 'string', 'max:100']]);
        $query = trim($data['q'] ?? '');
        $leads = collect();
        if ($query !== '') {
            $leads = Lead::query()->with('contact')->where(function ($builder) use ($query) {
                $builder->where('display_name', 'like', "%{$query}%")
                    ->orWhereHas('contact', fn ($contact) => $contact->where('phone_normalized', 'like', "%{$query}%"));
            })->latest()->limit(20)->get();
        }

        return view('admin.crm-inbox.show', compact('email', 'leads', 'query'));
    }

    public function link(Request $request, InboundEmail $email): RedirectResponse
    {
        $data = $request->validate(['lead_id' => ['required', 'integer', Rule::exists('leads', 'id')->whereNull('deleted_at')]]);
        $lead = Lead::findOrFail($data['lead_id']);
        DB::transaction(function () use ($email, $lead, $request) {
            $previousLead = $email->lead_id;
            $email->update(['lead_id' => $lead->id, 'status' => 'linked', 'linked_by_user_id' => $request->user()->id, 'linked_at' => now()]);
            if ($previousLead && $previousLead !== $lead->id) {
                Lead::find($previousLead)?->activities()->create([
                    'actor_user_id' => $request->user()->id, 'type' => 'email_unlinked',
                    'note' => 'Email masuk dipindahkan ke lead lain.',
                ]);
            }
            if ($previousLead !== $lead->id) {
                $lead->activities()->create([
                    'actor_user_id' => $request->user()->id, 'type' => 'email_linked',
                    'note' => 'Email dari '.$email->from_email.' ditautkan ke lead.',
                ]);
            }
        });

        return to_route('admin.crm.inbox.show', $email)->with('status', 'Email ditautkan ke lead.');
    }

    public function ignore(Request $request, InboundEmail $email): RedirectResponse
    {
        DB::transaction(function () use ($request, $email) {
            if ($email->lead_id) {
                $email->lead?->activities()->create([
                    'actor_user_id' => $request->user()->id, 'type' => 'email_unlinked',
                    'note' => 'Email masuk dilepas dari lead dan diabaikan.',
                ]);
            }
            $email->update(['status' => 'ignored', 'lead_id' => null, 'linked_by_user_id' => null, 'linked_at' => null]);
        });

        return to_route('admin.crm.inbox.show', $email)->with('status', 'Email ditandai tidak perlu ditangani.');
    }

    public function reopen(InboundEmail $email): RedirectResponse
    {
        if ($email->status === 'ignored') $email->update(['status' => 'new']);

        return to_route('admin.crm.inbox.show', $email)->with('status', 'Email dikembalikan ke inbox baru.');
    }
}
