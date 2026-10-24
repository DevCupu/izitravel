<?php

namespace App\Services;

use Webklex\PHPIMAP\Client;
use Webklex\PHPIMAP\ClientManager;

class MailboxReader
{
    private ?Client $client = null;

    private function folder(): \Webklex\PHPIMAP\Folder
    {
        if (! $this->client) {
            $this->client = (new ClientManager(config('imap')))->account();
            $this->client->connect();
        }

        return $this->client->getFolderByPath('INBOX')
            ?? throw new \RuntimeException('Folder INBOX tidak ditemukan.');
    }

    public function status(): array
    {
        $status = $this->folder()->examine();

        return [
            'uid_validity' => (int) ($status['uidvalidity'] ?? 0),
            'uid_next' => (int) ($status['uidnext'] ?? 1),
        ];
    }

    public function messagesAfter(int $lastUid): array
    {
        $messages = $this->folder()->query()
            ->whereUid(($lastUid + 1).':*')
            ->leaveUnread()->limit(25)->get();
        $result = [];

        foreach ($messages as $message) {
            if ((int) $message->uid <= $lastUid) continue;
            $sender = $message->from?->first();
            $body = trim($message->getTextBody());
            if ($body === '') {
                $body = html_entity_decode(strip_tags(str_replace(['<br>', '<br/>', '<br />', '</p>'], "\n", $message->getHTMLBody())), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }
            $result[] = [
                'uid' => (int) $message->uid,
                'message_id' => mb_substr((string) $message->message_id, 0, 255),
                'from_email' => mb_substr($sender?->mail ?: 'unknown', 0, 255),
                'from_name' => mb_substr($sender?->personal ?: '', 0, 255),
                'subject' => mb_substr((string) $message->subject, 0, 255),
                'body_text' => mb_substr(trim($body), 0, 100000),
                'received_at' => $message->date?->toDate(),
            ];
        }

        return $result;
    }
}
