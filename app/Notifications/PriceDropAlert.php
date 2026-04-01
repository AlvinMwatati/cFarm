<?php

namespace App\Notifications;

use App\Enums\KenyaCounty;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Commodity;

class PriceDropAlert extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public readonly Commodity $commodity,
        public readonly float     $previousPrice,
        public readonly float     $currentPrice,
        public readonly float     $changePercent,
        public readonly KenyaCounty $county,   )
    {

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $follow    = $notifiable->commodityFollows()->where('commodity_id', $this->commodity->id)->first();
        $channels  = ['database']; // always store in-app

        if ($follow?->via_email) $channels[] = 'mail';
        if ($follow?->via_sms)   $channels[] = 'africastalking';

        return $channels;;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("📉 Price Drop: {$this->commodity->name} in {$this->county}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("{$this->commodity->name} prices have dropped by **{$this->changePercent}%** in {$this->county}.")
            ->line("Previous price: KES " . number_format($this->previousPrice, 2) . " per {$this->commodity->unit->value}")
            ->line("Current price:  KES " . number_format($this->currentPrice, 2) . " per {$this->commodity->unit->value}")
            ->action('View Listings', route('listings.index', ['commodity_id' => $this->commodity->id]))
            ->line('This is a good time to buy.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
         return [
            'greeting'        => "Hello {$notifiable->name}!",
            'type'            => 'price_drop',
            'commodity_id'    => $this->commodity->id,
            'commodity_name'  => $this->commodity->name,
            'previous_price'  => $this->previousPrice,
            'current_price'   => $this->currentPrice,
            'change_percent'  => $this->changePercent,
            'county'          => $this->county,
            'message'         => "{$this->commodity->name} dropped {$this->changePercent}% in {$this->county}",
        ];
    }

    public function toSms(object $notifiable): array
    {
        return [
            'to'      => $notifiable->phone,
            'greeting' => "Hello {$notifiable->name}!",
            'message' => "cFarm Alert: {$this->commodity->name} price dropped {$this->changePercent}% in {$this->county}. "
                        . "Now KES " . number_format($this->currentPrice, 2) . "/{$this->commodity->unit->value}. "
                        . "Visit cfarm.co.ke for details."
    ];
    }
}
