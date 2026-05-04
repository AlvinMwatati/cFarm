<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function destroy(string $id, Request $request)
    {
        $request->user()->notifications()->findOrFail($id)->delete();
        return back()->with('success', 'Notification deleted.');
    }

    public function destroyAll(Request $request)
    {
        $request->user()->notifications()->delete();
        return back()->with('success', 'All notifications cleared.');
    }

    public function markAllRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }
}
