<?php

namespace App\Http\Controllers;

use App\Models\LostAndFound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LostAndFoundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'all');

        if ($type === 'returned') {
            $query = LostAndFound::where('status', 'resolved')->latest('resolved_at');
        } else {
            $query = LostAndFound::where('status', 'active')->latest();
            if ($type !== 'all') {
                $query->where('type', $type);
            }
        }

        $items = $query->paginate(12);

        $recentlyResolved = LostAndFound::where('status', 'resolved')
            ->latest('resolved_at')
            ->take(4)
            ->get();

        return view('lost-and-found.index', compact('items', 'type', 'recentlyResolved'));
    }


    public function show(LostAndFound $lostAndFound)
    {
        return view('lost-and-found.show', compact('lostAndFound'));
    }

    /**
     * Admin view to manage reports
     */
    public function adminIndex()
    {
        $items = LostAndFound::with('user')->latest()->get();

        return view('admin.lost-and-found.index', compact('items'));
    }

    /**
     * Show admin create form
     */
    public function adminCreate(Request $request)
    {
        $type = $request->get('type', 'lost');

        return view('admin.lost-and-found.create', compact('type'));
    }

    /**
     * Admin store report
     */
    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'type' => 'required|in:lost,found',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'contact_info' => 'nullable|string|max:255',
            'is_anonymous' => 'boolean',
            'reporter_name' => 'nullable|string|max:255',
            'owner_name' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('lost-and-found', 'public');
        }

        unset($validated['image']);
        // Automatically tag as anonymous if no reporter name is provided
        $validated['is_anonymous'] = $request->has('is_anonymous') || empty($validated['reporter_name']);

        $validated['user_id'] = Auth::id(); // Admin/Staff user
        $validated['status'] = 'active';
        $validated['date_reported'] = now();

        LostAndFound::create($validated);

        return redirect()->route('admin.lost-and-found.index')
            ->with('success', 'Report created successfully.');
    }

    /**
     * Show resolve form
     */
    public function resolve(LostAndFound $lost_and_found)
    {
        // Find potential matches from the opposite type that are still active
        $oppositeType = $lost_and_found->type == 'lost' ? 'found' : 'lost';
        $potentialMatches = LostAndFound::where('type', $oppositeType)
            ->where('status', 'active')
            ->latest()
            ->get();

        return view('admin.lost-and-found.resolve', compact('lost_and_found', 'potentialMatches'));
    }

    /**
     * Store resolution with proof
     */
    public function storeResolution(Request $request, LostAndFound $lost_and_found)
    {
        $validated = $request->validate([
            'identity_proof_ref' => 'required|string|max:255',
            'handover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'matched_item_id' => 'nullable|exists:lost_and_founds,id',
        ]);

        if ($request->hasFile('handover_image')) {
            $lost_and_found->handover_image_path = $request->file('handover_image')->store('handovers', 'public');
        }

        $lost_and_found->identity_proof_ref = $validated['identity_proof_ref'];
        $lost_and_found->matched_item_id = $validated['matched_item_id'];
        $lost_and_found->status = 'resolved';
        $lost_and_found->resolved_at = now();
        $lost_and_found->resolved_by = Auth::id();
        $lost_and_found->save();

        // If a matching report was selected, resolve it too
        if ($validated['matched_item_id']) {
            $matchedItem = LostAndFound::find($validated['matched_item_id']);
            if ($matchedItem->status == 'active') {
                $matchedItem->status = 'resolved';
                $matchedItem->resolved_at = now();
                $matchedItem->resolved_by = Auth::id();
                $matchedItem->matched_item_id = $lost_and_found->id; // Mutual link
                $matchedItem->identity_proof_ref = 'Resolved via matched report #'.$lost_and_found->id;
                $matchedItem->save();
            }
        }

        return redirect()->route('admin.lost-and-found.index')
            ->with('success', 'Item and its matching report (if any) have been marked as resolved.');
    }

    public function destroy(LostAndFound $lost_and_found)
    {
        if ($lost_and_found->image_path) {
            Storage::disk('public')->delete($lost_and_found->image_path);
        }
        if ($lost_and_found->handover_image_path) {
            Storage::disk('public')->delete($lost_and_found->handover_image_path);
        }

        $lost_and_found->delete();

        return redirect()->route('admin.lost-and-found.index')
            ->with('success', 'Report deleted successfully.');
    }
}
