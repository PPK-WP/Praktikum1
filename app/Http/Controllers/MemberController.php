<?php

namespace App\Http\Controllers;

use App\Models\ListModel; // Note: Model is named List based on standard JARA Project baseline, but we need to check its actual name
use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function store(Request $request, $listId)
    {
        $list = \App\Models\TaskList::findOrFail($listId);

        abort_unless($list->owner_id === auth()->id(), 403, 'Hanya owner yang dapat menambahkan anggota.');

        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'User tidak ditemukan');
        }

        if ($user->id === $list->owner_id) {
            return back()->with('error', 'Owner otomatis tergabung dalam workspace.');
        }

        if ($list->members()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'User sudah menjadi anggota.');
        }

        $list->members()->attach($user->id, ['joined_at' => now()]);

        return back()->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function destroy($listId, $userId)
    {
        $list = \App\Models\TaskList::findOrFail($listId);

        abort_unless($list->owner_id === auth()->id(), 403, 'Hanya owner yang dapat menghapus anggota.');

        $list->members()->detach($userId);

        return back()->with('success', 'Anggota berhasil dihapus.');
    }
}
