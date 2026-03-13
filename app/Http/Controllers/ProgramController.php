<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $programs = Program::orderBy('name')->get();
        if ($request->expectsJson()) {
            return response()->json(['programs' => $programs]);
        }
        return view('admin.programs.index', compact('programs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:50', 'unique:programs,name'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $program = Program::create([
            'name'        => strtoupper(trim($request->name)),
            'description' => $request->description,
            'is_active'   => true,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'program' => $program], 201);
        }
        return back()->with('success', 'Program added successfully.');
    }

    public function update(Request $request, Program $program)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:50', 'unique:programs,name,' . $program->id],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $program->update([
            'name'        => strtoupper(trim($request->name)),
            'description' => $request->description,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'program' => $program]);
        }
        return back()->with('success', 'Program updated successfully.');
    }

    public function archive(Program $program)
    {
        $program->update(['is_active' => false]);
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'program' => $program]);
        }
        return back()->with('success', "Program \"{$program->name}\" archived.");
    }

    public function restore(Program $program)
    {
        $program->update(['is_active' => true]);
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'program' => $program]);
        }
        return back()->with('success', "Program \"{$program->name}\" restored.");
    }
}
