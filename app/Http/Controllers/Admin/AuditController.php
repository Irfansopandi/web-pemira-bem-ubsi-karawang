<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditController extends Controller
{
    public function index(Request $request): View
    {
        $query = AuditLog::with('admin');

        if ($request->filled('action')) {
            $query->where('action', 'like', "%{$request->action}%");
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('admin', fn($sub) => $sub->where('name', 'like', "%{$search}%"));
            });
        }

        $logs = $query->latest('id')->paginate(20)->withQueryString();

        $actionTypes = AuditLog::select('action')->distinct()->pluck('action');

        return view('admin.audit.index', compact('logs', 'actionTypes'));
    }
}
