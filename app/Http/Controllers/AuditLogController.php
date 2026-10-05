<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        // BASE QUERY
        $query = AuditLog::with('user')->latest();

        // MODULE FILTER
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        // SEARCH FILTER
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $logs = $query->paginate(15)->withQueryString();

        return view('admin.audit-log.index', compact('logs'));
    }
}   