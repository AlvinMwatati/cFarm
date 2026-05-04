<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationLogController extends Controller
{
      public function index()
    {
        $notifications = DatabaseNotification::with('notifiable')
            ->latest()
            ->paginate(20);

        $stats = [
            'total'       => DatabaseNotification::count(),
            'unread'      => DatabaseNotification::whereNull('read_at')->count(),
            'price_drops' => DatabaseNotification::whereJsonContains('data->type', 'price_drop')->count(),
            'price_spikes'=> DatabaseNotification::whereJsonContains('data->type', 'price_spike')->count(),
            'new_listings'=> DatabaseNotification::whereJsonContains('data->type', 'new_listing')->count(),
            'weekly'      => DatabaseNotification::whereJsonContains('data->type', 'weekly_summary')->count(),
        ];

        return view('admin.notifications', compact('notifications', 'stats'));
    }
}
