<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStoreRequest;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\TaskActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function store(TaskStoreRequest $request, TaskList $list): RedirectResponse
    {
        abort_unless($list->isAccessibleBy(Auth::user()), 403);

        $validated = $request->validated();

        DB::transaction(function () use ($list, $validated) {
            $validated['created_by'] = Auth::id();
            $task = $list->tasks()->create($validated);

            TaskActivity::create([
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'action' => 'created',
            ]);
        });

        return redirect('/lists/' . $list->id)->with('success', 'Task berhasil ditambahkan.');
    }

    public function edit(TaskList $list, Task $task): View
    {
        abort_unless($list->isAccessibleBy(Auth::user()), 403);

        return view('tasks.edit', compact('list', 'task'));
    }

    public function update(TaskStoreRequest $request, TaskList $list, Task $task): RedirectResponse
    {
        abort_unless($list->isAccessibleBy(Auth::user()), 403);

        $validated = $request->validated();

        DB::transaction(function () use ($task, $validated) {
            $task->update($validated);

            TaskActivity::create([
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'action' => 'updated',
            ]);
        });

        return redirect('/lists/' . $list->id)->with('success', 'Task berhasil diperbarui.');
    }

    public function destroy(TaskList $list, Task $task): RedirectResponse
    {
        abort_unless($list->isAccessibleBy(Auth::user()), 403);

        DB::transaction(function () use ($task) {
            TaskActivity::create([
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'action' => 'deleted',
            ]);
            
            $task->delete();
        });

        return redirect('/lists/' . $list->id)->with('success', 'Task berhasil dihapus.');
    }
}

