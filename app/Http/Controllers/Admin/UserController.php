<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->search, fn ($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
            )
            ->when($request->filter === 'banned', fn ($q) => $q->where('is_banned', true))
            ->when($request->filter === 'active', fn ($q) => $q->where('is_banned', false))
            ->withCount('listings')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('listings.commodity');
        return view('admin.users.show', compact('user'));
    }

    public function ban(User $user)
    {
        if ($user->hasRole('admin')) {
            return back()->with('error', 'You cannot ban an admin user.');
        }

        $user->update([
            'is_banned' => true,
            'banned_at' => now(),
        ]);

        return back()->with('success', "{$user->name} has been banned.");
    }

    public function unban(User $user)
    {
        $user->update([
            'is_banned' => false,
            'banned_at' => null,
        ]);

        return back()->with('success', "{$user->name} has been unbanned.");
    }
}
