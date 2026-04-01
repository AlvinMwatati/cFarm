<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Listing;

class NewListingAlert extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public readonly Listing $listing,
    )
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $follow   = $notifiable->commodityFollows()
            ->where('commodity_id', $this->listing->commodity_id)
            ->first();
        $channels = ['database'];

        if ($follow?->via_email) $channels[] = 'mail';
        if ($follow?->via_sms)   $channels[] = 'africastalking';

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("🌾 New Listing: {$this->listing->commodity->name} in {$this->listing->county}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("A new listing for **{$this->listing->commodity->name}** is available in {$this->listing->county}.")
            ->line("Price: KES " . number_format($this->listing->price_per_unit, 2) . " per {$this->listing->commodity->unit->value}")
            ->line("Quantity: {$this->listing->quantity_available} {$this->listing->commodity->unit->value}s available")
            ->line("Seller: {$this->listing->user->name} — {$this->listing->user->phone}")
            ->action('View Listing', route('listings.show', $this->listing));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'greeting'       => "Hello {$notifiable->name}!",
            'type'           => 'new_listing',
            'listing_id'     => $this->listing->id,
            'commodity_id'   => $this->listing->commodity_id,
            'commodity_name' => $this->listing->commodity->name,
            'county'         => $this->listing->county,
            'price'          => $this->listing->price_per_unit,
            'message'        => "New {$this->listing->commodity->name} listing in {$this->listing->county}",
        ];
    }

    public function toSms(object $notifiable): array
    {
        return [
            'to'      => $notifiable->phone,
            'greeting' => "Hello {$notifiable->name}!",
            'message' => "New listing: {$this->listing->commodity->name} in {$this->listing->county}. "
                        . "Price: KES " . number_format($this->listing->price_per_unit, 2) . "/{$this->listing->commodity->unit->value}. "
                        . "Seller: {$this->listing->user->name} ({$this->listing->user->phone}). "
                        . "Visit cfarm.co.ke for details."
        ];
    }
}
