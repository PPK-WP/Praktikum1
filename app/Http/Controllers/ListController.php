<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ListController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $ownedLists = $user->lists()->withCount('tasks')->orderBy('name')->get();
        $memberLists = $user->memberLists()->withCount('tasks')->orderBy('name')->get();

        return view('lists.index', compact('ownedLists', 'memberLists'));
    }

    public function create(): View
    {
        return view('lists.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        TaskList::create([
            'name' => $validated['name'],
            'owner_id' => Auth::id(),
        ]);

        return redirect('/lists')->with('success', 'Workspace berhasil dibuat.');
    }

    public function show(TaskList $list): View
    {
        abort_unless($list->isAccessibleBy(Auth::user()), 403);

        $list->load(['tasks' => function ($query) {
            $query->orderBy('due_date');
        }]);

        return view('lists.show', compact('list'));
    }

    public function edit(TaskList $list): View
    {
        abort_unless($list->owner_id === Auth::id(), 403);

        return view('lists.edit', compact('list'));
    }

    public function update(Request $request, TaskList $list): RedirectResponse
    {
        abort_unless($list->owner_id === Auth::id(), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $list->update($validated);

        return redirect('/lists/' . $list->id)->with('success', 'Workspace berhasil diperbarui.');
    }

    public function destroy(TaskList $list): RedirectResponse
    {
        abort_unless($list->owner_id === Auth::id(), 403);

        $list->delete();

        return redirect('/lists')->with('success', 'Workspace berhasil dihapus.');
    }
}
