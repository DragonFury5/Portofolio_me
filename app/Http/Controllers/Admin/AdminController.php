<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ReportController as PublicReportController;
use App\Models\Portfolio;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorizeStaff($request);

        return view('admin.index', [
            'reports' => Report::query()
                ->where('status', 'open')
                ->with(['portfolio.user'])
                ->latest()
                ->limit(30)
                ->get(),
            'recentPages' => Portfolio::query()
                ->with('user')
                ->latest('updated_at')
                ->limit(12)
                ->get(),
            'reasons' => PublicReportController::REASONS,
            'makers' => Portfolio::query()->distinct('user_id')->count('user_id'),
            'pages' => Portfolio::count(),
            'livePages' => Portfolio::published()->count(),
        ]);
    }

    public function toggleFeature(Request $request, Portfolio $portfolio)
    {
        $this->authorizeStaff($request);

        $portfolio->update(['is_featured' => ! $portfolio->is_featured]);

        return back()->with('status', $portfolio->is_featured
            ? 'Page featured on the home page.'
            : 'Page removed from the featured shelf.');
    }

    public function unpublish(Request $request, Portfolio $portfolio)
    {
        $this->authorizeStaff($request);

        $portfolio->update(['is_published' => false]);

        return back()->with('status', 'Page taken back to draft.');
    }

    public function resolve(Request $request, Report $report)
    {
        $this->authorizeStaff($request);

        $report->update([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);

        return back()->with('status', 'Report marked as resolved.');
    }

    private function authorizeStaff(Request $request): void
    {
        abort_unless($request->user()?->is_admin, 403);
    }
}
