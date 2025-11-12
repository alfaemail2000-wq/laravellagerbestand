<?php

namespace App\Notifications;

use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TargetStockReachedNotification extends Notification
{
    use Queueable;

    public Item $item;

    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("✅ Zielbestand erreicht: {$this->item->name}")
            ->line("Das Fertigprodukt {$this->item->name} (SKU: {$this->item->sku}) hat den Zielbestand erreicht oder überschritten.")
            ->line("Aktueller Bestand: {$this->item->totalStock()} / Zielbestand: {$this->item->target_stock}")
            ->line("Weitere Mails werden erst gesendet, wenn der Bestand wieder unter den Zielwert fällt.");
    }
}
