<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    /**
     * Hanya ubah nama & email. Hapus akun sendiri sengaja TIDAK disediakan —
     * akun hanya dihapus oleh Admin (SRS-008).
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'lowercase', 'email', 'max:255',
                Rule::unique('users')->ignore($request->user()->id),
            ],
        ]);

        $request->user()->update($validated);

        return back()->with('status', 'profile-updated');
    }
}
