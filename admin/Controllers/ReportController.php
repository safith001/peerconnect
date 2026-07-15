<?php

namespace Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with('reportable', 'reporter', 'handler')
            ->latest()
            ->paginate(20);

        return view('admin::reports.index', compact('reports'));
    }

    public function dismiss(Report $report)
    {
        $report->update([
            'status' => 'dismissed',
            'handled_by' => auth()->id(),
            'handled_at' => now(),
        ]);

        return back()->with('success', 'Report dismissed.');
    }

    public function actionTaken(Report $report, Request $request)
    {
        $request->validate(['note' => 'required|string|max:1000']);

        $report->update([
            'status' => 'action_taken',
            'handled_by' => auth()->id(),
            'handled_at' => now(),
        ]);

        return back()->with('success', 'Report marked as action taken.');
    }
}
