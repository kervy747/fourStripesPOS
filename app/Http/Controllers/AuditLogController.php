<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        // GATE — admin only
        abort_unless(auth()->user()->isAdmin(), 403);

        $query = AuditLog::with('user')->latest();

        // FILTER BY ACTION
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // FILTER BY DATE
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('admin.audit-log.index', compact('logs'));
    }
}