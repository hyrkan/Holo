<?php

namespace App\Http\Controllers;

use App\Models\EnrollmentStatus;
use Illuminate\Http\Request;

class EnrollmentStatusController extends Controller
{
    public function index(Request $request)
    {
        $statuses = EnrollmentStatus::orderBy('label')->get();
        if ($request->expectsJson()) {
            return response()->json(['statuses' => $statuses]);
        }
        return view('admin.enrollment-statuses.index', compact('statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:50', 'unique:enrollment_statuses,name', 'regex:/^[a-z_]+$/'],
            'label' => ['required', 'string', 'max:100'],
        ]);

        $status = EnrollmentStatus::create([
            'name'      => strtolower(trim($request->name)),
            'label'     => ucwords(trim($request->label)),
            'is_active' => true,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'status' => $status], 201);
        }
        return back()->with('success', 'Enrollment status added successfully.');
    }

    public function update(Request $request, EnrollmentStatus $enrollmentStatus)
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:50', 'regex:/^[a-z_]+$/', 'unique:enrollment_statuses,name,' . $enrollmentStatus->id],
            'label' => ['required', 'string', 'max:100'],
        ]);

        $enrollmentStatus->update([
            'name'  => strtolower(trim($request->name)),
            'label' => ucwords(trim($request->label)),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'status' => $enrollmentStatus]);
        }
        return back()->with('success', 'Enrollment status updated successfully.');
    }

    public function archive(EnrollmentStatus $enrollmentStatus)
    {
        $enrollmentStatus->update(['is_active' => false]);
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'status' => $enrollmentStatus]);
        }
        return back()->with('success', "Status \"{$enrollmentStatus->label}\" archived.");
    }

    public function restore(EnrollmentStatus $enrollmentStatus)
    {
        $enrollmentStatus->update(['is_active' => true]);
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'status' => $enrollmentStatus]);
        }
        return back()->with('success', "Status \"{$enrollmentStatus->label}\" restored.");
    }
}
