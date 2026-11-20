<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ElectionPeriod;
use App\Services\ElectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(
        protected ElectionService $electionService
    ) {}

    /**
     * Display system settings and configuration overview.
     */
    public function index(): View
    {
        $activePeriod = $this->electionService->getActivePeriod();
        $totalPeriods = ElectionPeriod::count();

        // System Environment Details
        $serverInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug') ? 'Aktif (Development)' : 'Nonaktif (Production)',
            'db_connection' => config('database.default'),
            'timezone' => config('app.timezone', 'Asia/Jakarta'),
            'session_driver' => config('session.driver'),
        ];

        return view('admin.settings.index', [
            'activePeriod' => $activePeriod,
            'totalPeriods' => $totalPeriods,
            'serverInfo' => $serverInfo,
        ]);
    }
}
