<?php

namespace App\Domains\Complaint\Notifications;

use App\Domains\Complaint\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewComplaintSubmitted extends Notification
{
    use Queueable;

    public function __construct(public readonly Complaint $complaint) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Keluhan baru '.$this->complaint->reference_code)
            ->line('Keluhan baru telah diterima untuk brand '.$this->complaint->brand->name.'.')
            ->line('Subjek: '.$this->complaint->subject)
            ->line('Referensi: '.$this->complaint->reference_code)
            ->line('Buka panel admin untuk meninjau dan menanganinya.');
    }
}
