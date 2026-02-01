<?php

namespace App\Http\Controllers;

use App\Mail\LostAndFoundResolvedMail;
use App\Models\LostAndFound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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

    public function adminShow(LostAndFound $lost_and_found)
    {
        $lost_and_found->load(['user', 'resolver', 'matchedItem']);

        return view('admin.lost-and-found.show', compact('lost_and_found'));
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
     * Store report from student or public
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'type' => 'required|in:lost,found',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'contact_info' => 'required|email|max:255',
            'is_anonymous' => 'boolean',
            'reporter_name' => 'required|string|max:255',
            'owner_name' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('lost-and-found', 'public');
        }

        unset($validated['image']);
        $validated['is_anonymous'] = false; // Always false now

        // Associate with logged in user (student or employee/admin)
        if (Auth::guard('student')->check()) {
            $validated['user_id'] = Auth::guard('student')->id();
            $validated['type'] = 'lost'; // Students can only report lost items
        } elseif (Auth::check()) {
            $validated['user_id'] = Auth::id();
        }

        $validated['status'] = 'active';
        $validated['date_reported'] = now();

        LostAndFound::create($validated);

        if (Auth::guard('student')->check()) {
            return redirect()->route('student.lost-and-found.my-reports')
                ->with('success', 'Your report has been submitted successfully.');
        }

        if (Auth::check()) {
            return redirect()->route('admin.lost-and-found.index')
                ->with('success', 'Report created successfully.');
        }

        return redirect()->route('lost-and-found.index')
            ->with('success', 'Report submitted successfully. Thank you for your help!');
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
            'contact_info' => 'required|email|max:255',
            'is_anonymous' => 'boolean',
            'reporter_name' => 'required|string|max:255',
            'owner_name' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('lost-and-found', 'public');
        }

        unset($validated['image']);
        // Automatically tag as anonymous if requested
        $validated['is_anonymous'] = $request->has('is_anonymous');

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
            'handover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'matched_item_id' => 'nullable|exists:lost_and_founds,id',
            'returned_by_name' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('handover_image')) {
            $lost_and_found->handover_image_path = $request->file('handover_image')->store('handovers', 'public');
        }

        $lost_and_found->matched_item_id = $validated['matched_item_id'];
        $lost_and_found->returned_by_name = $validated['returned_by_name'];
        $lost_and_found->status = 'resolved';
        $lost_and_found->resolved_at = now();
        $lost_and_found->resolved_by = Auth::id();
        $lost_and_found->save();

        // Notify reporter if email is provided
        $recipient = trim($lost_and_found->contact_info);
        if ($recipient && filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($recipient)->send(new LostAndFoundResolvedMail($lost_and_found));
            } catch (\Exception $e) {
                // Log error or ignore if mail fails
                \Log::error("Failed to send Lost and Found resolution email to {$recipient}: ".$e->getMessage());
            }
        }

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

                // Notify reporter of the matched item too if email is provided
                $matchedRecipient = trim($matchedItem->contact_info);
                if ($matchedRecipient && filter_var($matchedRecipient, FILTER_VALIDATE_EMAIL)) {
                    try {
                        Mail::to($matchedRecipient)->send(new LostAndFoundResolvedMail($matchedItem));
                    } catch (\Exception $e) {
                        \Log::error("Failed to send matched Lost and Found resolution email to {$matchedRecipient}: ".$e->getMessage());
                    }
                }
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
