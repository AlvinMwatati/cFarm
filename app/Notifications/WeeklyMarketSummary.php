<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Number;

class WeeklyMarketSummary extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public readonly Collection $summaries,
    )
    {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Weekly summary always goes via email if enabled, plus in-app
        $channels = ['database', 'mail'];
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('📊 Your Weekly cFarm Market Summary')
            ->greeting("Hello {$notifiable->name}!")
            ->line('Here is your weekly price summary for commodities you follow:');

        foreach ($this->summaries as $summary) {

            if ($this->summaries->isEmpty()) {
            $mail->line("No price changes to report this week.");
            }

            $trend = $summary['change'] > 0 ? '📈 +' : '📉 ';
            // Format the price as KES (Kenyan Shilling)
             $formattedPrice = Number::currency($summary['current_price'], in: 'KES');

            // Format the change as a percentage with 2 decimal places
            $formattedChange = Number::percentage($summary['change'], precision: 2);
            $mail->line(
                "**{$summary['commodity']}**: KES {$formattedPrice} " .
                "({$trend}{$formattedChange} this week)"
            );

        }

        $mail->action('View Full Insights', route('insights.index'));

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'greeting'  => "Hello {$notifiable->name}!",
            'type'      => 'weekly_summary',
            'summaries' => $this->summaries->toArray(),
            'message'   => 'Your weekly market summary is ready',
        ];
    }

    public function toSms(object $notifiable): array
    {
        $message = "cFarm Weekly Summary:\n";
        foreach ($this->summaries as $summary) {
            $trend = $summary['change'] > 0 ? '📈 +' : '📉 ';
            $formattedPrice = Number::currency($summary['current_price'], in: 'KES');
            $formattedChange = Number::percentage($summary['change'], precision: 2);
            $message .= "**{$summary['commodity']}**: KES {$formattedPrice} ({$trend}{$formattedChange})\n";
        }
        return [
            'to' => $notifiable->phone,
            'message' => $message . "Visit cfarm.co.ke for details."
        ];
    }
}
