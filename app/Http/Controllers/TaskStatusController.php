<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskStatusController extends Controller
{
    public function toggle(Task $task)
    {
        abort_unless($task->list->isAccessibleBy(auth()->user()), 403);

        $task->update([
            'status' => $task->status === 'todo' ? 'done' : 'todo'
        ]);

        return redirect('/lists/' . $task->list_id);
    }
}
