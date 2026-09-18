<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MyTaskController extends Controller
{
    /**
     * Kolom yang boleh dipakai untuk ORDER BY (whitelist).
     * Key = nilai dari query string, value = kolom SQL sebenarnya.
     */
    private const SORTABLE = [
        'title' => 'tasks.title',
        'due_date' => 'tasks.due_date',
        'priority' => 'tasks.priority',
        'status' => 'tasks.status',
    ];

    /**
     * SRS-010: daftar task yang dibuat user login + pencarian & sort aman.
     */
    public function index(Request $request): View
    {
        // Hanya task milikku (created_by = Auth::id()) — task orang lain tidak pernah ikut.
        $query = Auth::user()->createdTasks()->with(['list.owner', 'creator']);

        // Pencarian: Prepared Statement eksplisit — nilai user hanya lewat binding "?".
        $q = is_string($request->query('q')) ? trim($request->query('q')) : '';
        if ($q !== '') {
            $query->whereRaw('(tasks.title LIKE ? OR tasks.description LIKE ?)', ["%{$q}%", "%{$q}%"]);
        }

        // Sort dinamis: kolom hanya dari whitelist, arah hanya asc/desc; selain itu fallback default.
        $sort = $request->query('sort');
        $sort = is_string($sort) && array_key_exists($sort, self::SORTABLE) ? $sort : 'due_date';

        $dir = $request->query('dir');
        $dir = in_array($dir, ['asc', 'desc'], true) ? $dir : 'asc';

        $tasks = $query->orderBy(self::SORTABLE[$sort], $dir)
            ->paginate(15)
            ->withQueryString();

        return view('mytasks.index', compact('tasks', 'q', 'sort', 'dir'));
    }
}
