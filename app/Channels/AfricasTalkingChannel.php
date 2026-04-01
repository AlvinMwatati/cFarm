<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class AfricasTalkingChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        if (!method_exists($notification, 'toSms')) {
            return;
        }

        $phone   = $notifiable->phone;
        /** @var mixed $notification */
        $message = $notification->toSms($notifiable);

        if (empty($phone) || empty($message)) {
            return;
        }

        try {
            $AT       = new \AfricasTalking\SDK\AfricasTalking(
                config('services.africastalking.username'),
                config('services.africastalking.api_key')
            );
            $sms      = $AT->sms();
            $response = $sms->send([
                'to'      => $phone,
                'message' => $message,
            ]);

            Log::info('SMS sent via Africa\'s Talking', [
                'to'       => $phone,
                'response' => $response,
            ]);
        } catch (\Exception $e) {
            Log::error('Africa\'s Talking SMS failed', [
                'to'    => $phone,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
