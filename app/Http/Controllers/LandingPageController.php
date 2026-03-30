<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LandingPageController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::query()
            ->where('is_active', true)
            ->where('is_draft', false)
            ->where('is_archived', false);

        // Filter by Category (Target Audience)
        if ($request->filled('category')) {
            $query->where('target_audience', $request->category);
        }

        // Filter by Year Level
        if ($request->filled('year_level')) {
            $yearLevel = $request->year_level;
            $query->where(function($q) use ($yearLevel) {
                $q->where('target_audience', 'all')
                  ->orWhereJsonContains('target_year_levels', $yearLevel);
            });
        }

        // Filter by Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('start_date', '>=', Carbon::parse($request->date_from));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('start_date', '<=', Carbon::parse($request->date_to));
        }

        $announcements = $query->with('attachments')->latest('start_date')->paginate(9);

        if ($request->ajax()) {
            return view('partials._announcements_grid', compact('announcements'))->render();
        }

        return view('welcome', compact('announcements'));
    }
}
