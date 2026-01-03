<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Certificate;
use App\Models\Student;
use App\Models\CertificateSignatory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function edit(Event $event)
    {
        $certificate = $event->certificate()->with('signatories')->first() ?? new Certificate();
        return view('admin.events.certificate.edit', compact('event', 'certificate'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'required|string|max:255',
            'body' => 'required|string',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'signatories' => 'nullable|array',
            'signatories.*.name' => 'required|string|max:255',
            'signatories.*.label' => 'nullable|string|max:255',
            'signatories.*.signature_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $certData = $request->only([
            'title', 'sub_title', 'body',
        ]);
        
        $certData['is_active'] = $request->has('is_active');

        if ($request->hasFile('background_image')) {
            if ($event->certificate && $event->certificate->background_image) {
                Storage::disk('public')->delete($event->certificate->background_image);
            }
            $certData['background_image'] = $request->file('background_image')->store('certificates', 'public');
        }

        $certificate = $event->certificate()->updateOrCreate(['event_id' => $event->id], $certData);

        // Handle Signatories
        $existingSignatoryIds = $certificate->signatories()->pluck('id')->toArray();
        $submittedSignatoryIds = [];

        if ($request->has('signatories')) {
            foreach ($request->signatories as $index => $sigData) {
                $signatoryId = $sigData['id'] ?? null;
                $updateData = [
                    'name' => $sigData['name'],
                    'label' => $sigData['label'],
                    'order' => $index,
                ];

                if (isset($sigData['signature_image'])) {
                    $file = $sigData['signature_image'];
                    $path = $file->store('signatures', 'public');
                    $updateData['signature_image'] = $path;

                    // Delete old signature if updating
                    if ($signatoryId) {
                        $oldSignatory = CertificateSignatory::find($signatoryId);
                        if ($oldSignatory && $oldSignatory->signature_image) {
                            Storage::disk('public')->delete($oldSignatory->signature_image);
                        }
                    }
                }

                $signatory = $certificate->signatories()->updateOrCreate(
                    ['id' => $signatoryId],
                    $updateData
                );
                
                $submittedSignatoryIds[] = $signatory->id;
            }
        }

        // Delete removed signatories
        $toDelete = array_diff($existingSignatoryIds, $submittedSignatoryIds);
        if (!empty($toDelete)) {
            $signatoriesToDelete = CertificateSignatory::whereIn('id', $toDelete)->get();
            foreach ($signatoriesToDelete as $sig) {
                if ($sig->signature_image) {
                    Storage::disk('public')->delete($sig->signature_image);
                }
                $sig->delete();
            }
        }

        return redirect()->route('admin.events.show', $event)->with('success', 'Certificate template updated successfully.');
    }

    public function preview(Event $event)
    {
        $certificate = $event->certificate()->with('signatories')->first();
        if (!$certificate) {
            return back()->with('error', 'Certificate template not found.');
        }

        $student = (object)[
            'full_name' => 'JUAN DELA CRUZ',
            'program' => 'BS Information Technology'
        ];

        return view('admin.events.certificate.preview', compact('event', 'certificate', 'student'));
    }

    public function download(Event $event)
    {
        // If student, check eligibility
        if (auth()->guard('student')->check()) {
            $student = auth()->guard('student')->user()->student;
            
            // Check if student is eligible for certificate
            $registration = \App\Models\EventRegistration::where('event_id', $event->id)
                ->where('student_id', $student->id)
                ->first();

            if (!$registration || !$registration->is_eligible_for_certificate) {
                return back()->with('error', 'You are not eligible for a certificate. Please contact the event administrator.');
            }
        } else {
            return back()->with('error', 'Unauthorized.');
        }

        $certificate = $event->certificate()->with('signatories')->first();
        if (!$certificate || !$certificate->is_active) {
            return back()->with('error', 'Certificate is not yet available.');
        }

        return view('admin.events.certificate.preview', compact('event', 'certificate', 'student'));
    }

    public function toggleEligibility(Request $request, Event $event, Student $student)
    {
        $registration = \App\Models\EventRegistration::where('event_id', $event->id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        $registration->update([
            'is_eligible_for_certificate' => !$registration->is_eligible_for_certificate
        ]);

        return back()->with('success', 'Participant eligibility updated.');
    }

    public function bulkEligibility(Request $request, Event $event)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'action' => 'required|in:award,revoke'
        ]);

        \App\Models\EventRegistration::where('event_id', $event->id)
            ->whereIn('student_id', $request->student_ids)
            ->update([
                'is_eligible_for_certificate' => $request->action === 'award'
            ]);

        return back()->with('success', 'Selected participants eligibility updated successfully.');
    }
}
