<?php

declare(strict_types=1);

namespace KingsCode\DevTools\Notifications;

use Illuminate\Bus\Queueable;
use NotificationChannels\Apn\ApnMessage;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification;

class TestNotification extends \App\Notifications\Candidate\CandidateNotification
{
    use Queueable;

    private ?string $title;

    private ?string $body;

    private ?string $data;

    public function __construct(?string $title, ?string $body, ?array $data = [])
    {
        $this->title = $title;
        $this->body = $body;
        $this->data = $data;
    }

    public function toApn(): ApnMessage
    {
        return ApnMessage::create()
            ->title($this->getTitle())
            ->body($this->getBody())
            ->setCustom($this->getCustomData());
    }

    public function toFcm(): FcmMessage
    {
        $notification = Notification::create()
            ->setTitle($this->getTitle())
            ->setBody($this->getBody());

        return FcmMessage::create()
            ->setNotification($notification)
            ->setData($this->getCustomData());
    }

    private function getCustomData(): array
    {
        return $data ?? [];
    }

    protected function getTitle(): string
    {
        return $title ?? 'Test Notification Title';
    }

    protected function getBody(): string
    {
        return $body ?? 'Test body';
    }
}