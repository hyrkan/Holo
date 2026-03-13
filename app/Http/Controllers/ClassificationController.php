<?php

namespace App\Http\Controllers;

use App\Models\Classification;
use Illuminate\Http\Request;

class ClassificationController extends Controller
{
    public function index(Request $request)
    {
        $classifications = Classification::orderBy('label')->get();
        if ($request->expectsJson()) {
            return response()->json(['classifications' => $classifications]);
        }
        return view('admin.classifications.index', compact('classifications'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:50', 'unique:classifications,name', 'regex:/^[a-z_]+$/'],
            'label' => ['required', 'string', 'max:100'],
        ]);

        $classification = Classification::create([
            'name'      => strtolower(trim($request->name)),
            'label'     => ucwords(trim($request->label)),
            'is_active' => true,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'classification' => $classification], 201);
        }
        return back()->with('success', 'Classification added successfully.');
    }

    public function update(Request $request, Classification $classification)
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:50', 'regex:/^[a-z_]+$/', 'unique:classifications,name,' . $classification->id],
            'label' => ['required', 'string', 'max:100'],
        ]);

        $classification->update([
            'name'  => strtolower(trim($request->name)),
            'label' => ucwords(trim($request->label)),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'classification' => $classification]);
        }
        return back()->with('success', 'Classification updated successfully.');
    }

    public function archive(Classification $classification)
    {
        $classification->update(['is_active' => false]);
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'classification' => $classification]);
        }
        return back()->with('success', "Classification \"{$classification->label}\" archived.");
    }

    public function restore(Classification $classification)
    {
        $classification->update(['is_active' => true]);
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'classification' => $classification]);
        }
        return back()->with('success', "Classification \"{$classification->label}\" restored.");
    }
}
