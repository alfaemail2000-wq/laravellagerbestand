<?php

namespace App\Notifications;

use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LowStockNotification extends Notification
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
            ->subject("⚠️ Niedriger Bestand: {$this->item->name}")
            ->line("Der Bestand von {$this->item->name} (SKU: {$this->item->sku}) liegt unter dem Mindestbestand.")
            ->line("Aktueller Bestand: {$this->item->totalStock()} / Mindestbestand: {$this->item->min_stock}")
            ->line("Bitte rechtzeitig nachbestellen.");
    }
}
