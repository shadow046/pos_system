<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class ActivityLogController extends Controller
{
    // Display user's activity logs
    public function index(User $user): Response
    {
        return Inertia::render('ActivityLogs/Index', [
            'user' => $user->load(['profile']),
            'activity_logs' => ActivityLog::byUser($user->id)
                ->latest('created_at')
                ->get()
                ->groupBy(function ($date) {
                    return date('F d, Y D', strtotime($date->created_at));
                }),
            'last_login' => ActivityLog::byUser($user->id)->byAction('Login')->latest('created_at')->first(),
            'last_logout' => ActivityLog::byUser($user->id)->byAction('Logout')->latest('created_at')->first(),
        ]);
    }
}
