<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        if (auth()->user()->isAdmin()) {
            return redirect('/admin/users');
        }

        return redirect('/lists');
    }
}
