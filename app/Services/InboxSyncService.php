<?php

namespace App\Services;

use App\Models\InboundMailbox;
use Illuminate\Support\Facades\Log;

class InboxSyncService
{
    public function __construct(private MailboxReader $reader) {}

    public function sync(): int
    {
        $mailbox = InboundMailbox::firstOrCreate(['account' => 'default']);

        try {
            $status = $this->reader->status();
            if (! $status['uid_validity']) throw new \RuntimeException('Server IMAP tidak mengirim UIDVALIDITY.');

            if ($mailbox->uid_validity === null || $mailbox->uid_validity !== $status['uid_validity']) {
                // First activation, or a mailbox reset: start after existing messages.
                $mailbox->update([
                    'uid_validity' => $status['uid_validity'],
                    'last_uid' => max(0, $status['uid_next'] - 1),
                    'last_synced_at' => now(),
                    'last_error' => null,
                ]);

                return 0;
            }

            $count = 0;
            foreach ($this->reader->messagesAfter($mailbox->last_uid) as $message) {
                $uid = (int) $message['uid'];
                if ($uid <= $mailbox->last_uid) continue;
                $mailbox->emails()->firstOrCreate(
                    ['uid_validity' => $mailbox->uid_validity, 'uid' => $uid],
                    array_diff_key($message, ['uid' => true]),
                );
                $mailbox->last_uid = $uid;
                $mailbox->save();
                $count++;
            }
            $mailbox->update(['last_synced_at' => now(), 'last_error' => null]);

            return $count;
        } catch (\Throwable $exception) {
            $message = $exception->getMessage();
            $secret = (string) config('imap.accounts.default.password');
            if ($secret !== '') $message = str_replace($secret, '[redacted]', $message);
            $mailbox->update(['last_error' => mb_substr($message, 0, 1000)]);
            Log::error('Sinkronisasi inbox CRM gagal.', ['exception' => $exception]);
            throw $exception;
        }
    }
}
