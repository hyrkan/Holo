<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\LostAndFound;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : now()->subDays(29)->startOfDay();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : now()->endOfDay();

        // Summary Stats (Filtered by date range where applicable)
        $stats = [
            'total_students' => Student::whereBetween('created_at', [$startDate, $endDate])->count(),
            'pending_students' => Student::where('status', Student::STATUS_PENDING)->whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_events' => Event::whereBetween('created_at', [$startDate, $endDate])->count(),
            'active_events' => Event::whereHas('eventDates', function($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()]);
            })->count(),
            'total_lost' => LostAndFound::where('type', 'lost')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_found' => LostAndFound::where('type', 'found')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'resolved_lost_found' => LostAndFound::where('status', 'resolved')->whereBetween('resolved_at', [$startDate, $endDate])->count(),
        ];

        // Event Scheduling Analytics
        $schedulingStats = [
            'this_week' => Event::whereHas('eventDates', function($query) {
                $query->whereBetween('date', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()]);
            })->count(),
            'this_month' => Event::whereHas('eventDates', function($query) {
                $query->whereMonth('date', now()->month)->whereYear('date', now()->year);
            })->count(),
            'this_year' => Event::whereHas('eventDates', function($query) {
                $query->whereYear('date', now()->year);
            })->count(),
        ];

        // Registration Trends (Filtered by date range)
        $registrations = EventRegistration::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->get()
            ->pluck('count', 'date');

        $registrationTrends = collect();
        $days = $startDate->diffInDays($endDate);
        for ($i = $days; $i >= 0; $i--) {
            $date = (clone $endDate)->subDays($i)->toDateString();
            $registrationTrends->push([
                'date' => $date,
                'count' => $registrations->get($date, 0)
            ]);
        }

        // Attendance Stats
        $totalRegistrations = EventRegistration::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalAttendances = Attendance::whereBetween('created_at', [$startDate, $endDate])->count();
        $attendanceRate = $totalRegistrations > 0 ? round(($totalAttendances / $totalRegistrations) * 100, 2) : 0;

        // Lost and Found Status Breakdown
        $lostAndFoundStats = [
            'lost_active' => LostAndFound::where('type', 'lost')->where('status', 'active')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'lost_resolved' => LostAndFound::where('type', 'lost')->where('status', 'resolved')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'found_active' => LostAndFound::where('type', 'found')->where('status', 'active')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'found_resolved' => LostAndFound::where('type', 'found')->where('status', 'resolved')->whereBetween('created_at', [$startDate, $endDate])->count(),
        ];

        // Recent Updates
        $recentStudents = Student::whereBetween('created_at', [$startDate, $endDate])->latest()->take(5)->get();
        $recentLostFound = LostAndFound::whereBetween('created_at', [$startDate, $endDate])->latest()->take(5)->get();
        
        // Events with most registrations in this period
        $popularEvents = Event::withCount(['registrations' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->orderBy('registrations_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 
            'registrationTrends', 
            'attendanceRate', 
            'lostAndFoundStats', 
            'recentStudents', 
            'recentLostFound',
            'popularEvents',
            'startDate',
            'endDate',
            'schedulingStats'
        ));
    }
}
