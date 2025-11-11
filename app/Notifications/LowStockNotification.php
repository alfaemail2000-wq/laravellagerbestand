<?php

namespace App\Notifications;

use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;

    public function __construct(public Item $item) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Meldebestand unterschritten: {$this->item->name}")
            ->line("Artikel: {$this->item->name} ({$this->item->sku})")
            ->line("Aktueller Bestand: {$this->item->totalStock()} Stück")
            ->line("Mindestbestand: {$this->item->min_stock}")
            ->line('Bitte Nachschub bestellen.');
    }
}
