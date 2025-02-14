<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PendingApprovalController extends Controller
{
    public function __invoke()
    {
        if (auth()->user()->isApproved()) {
            return redirect()->to(route('dashboard'));
        }

        return view('notice.pending-approval');
    }
}
