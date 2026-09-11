<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function store(Request $request, TaskList $list): RedirectResponse
    {
        abort_unless($list->isAccessibleBy(Auth::user()), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'priority' => ['required', 'in:penting,sedang,rendah'],
            'due_date' => ['required', 'date'],
        ]);

        $list->tasks()->create($validated);

        return redirect('/lists/' . $list->id)->with('success', 'Task berhasil ditambahkan.');
    }

    public function edit(TaskList $list, Task $task): View
    {
        abort_unless($list->isAccessibleBy(Auth::user()), 403);

        return view('tasks.edit', compact('list', 'task'));
    }

    public function update(Request $request, TaskList $list, Task $task): RedirectResponse
    {
        abort_unless($list->isAccessibleBy(Auth::user()), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'priority' => ['required', 'in:penting,sedang,rendah'],
            'due_date' => ['required', 'date'],
        ]);

        $task->update($validated);

        return redirect('/lists/' . $list->id)->with('success', 'Task berhasil diperbarui.');
    }

    public function destroy(TaskList $list, Task $task): RedirectResponse
    {
        abort_unless($list->isAccessibleBy(Auth::user()), 403);

        $task->delete();

        return redirect('/lists/' . $list->id)->with('success', 'Task berhasil dihapus.');
    }
}
