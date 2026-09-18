<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskStatusController extends Controller
{
    public function toggle(Task $task)
    {
        abort_unless($task->list->isAccessibleBy(auth()->user()), 403);
        abort_unless($task->list->isAccessibleBy(Auth::user()), 403);

        $task->update([
            'status' => $task->status === 'todo' ? 'done' : 'todo'
        ]);
        DB::transaction(function () use ($task) {
            $newStatus = $task->status === 'todo' ? 'done' : 'todo';
            $task->update([
                'status' => $newStatus
            ]);

            TaskActivity::create([
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'action' => $newStatus === 'done' ? 'completed' : 'updated',
            ]);
        });

        return redirect('/lists/' . $task->list_id);
    }
}
