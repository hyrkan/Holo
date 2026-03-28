<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Certificate;
use App\Models\Student;
use App\Models\CertificateSignatory;
use App\Models\EventRegistration;
use App\Mail\CertificateAwardedMail;
use App\Helpers\Messenger;
use App\Helpers\ImageStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function index(Event $event)
    {
        $certificates = $event->certificates;
        return view('admin.events.certificate.index', compact('event', 'certificates'));
    }

    public function create(Event $event)
    {
        $certificate = new Certificate();
        return view('admin.events.certificate.edit', compact('event', 'certificate'));
    }

    public function store(Request $request, Event $event)
    {
        return $this->save($request, $event, new Certificate());
    }

    public function edit(Event $event, Certificate $certificate)
    {
        $certificate->load('signatories');
        return view('admin.events.certificate.edit', compact('event', 'certificate'));
    }

    public function update(Request $request, Event $event, Certificate $certificate)
    {
        return $this->save($request, $event, $certificate);
    }

    protected function save(Request $request, Event $event, Certificate $certificate)
    {
        $request->validate([
            'name' => 'required|string|max:255',
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

        $data = $request->only(['name', 'title', 'sub_title', 'body']);
        $data['is_active'] = $request->has('is_active');
        $data['event_id'] = $event->id;

        if ($request->hasFile('background_image')) {
            ImageStorage::delete($certificate->background_image);
            $data['background_image'] = ImageStorage::upload($request->file('background_image'), 'certificates');
        }

        $certificate->fill($data);
        $certificate->save();

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
                    $path = ImageStorage::upload($file, 'signatures');
                    $updateData['signature_image'] = $path;

                    if ($signatoryId) {
                        $oldSignatory = CertificateSignatory::find($signatoryId);
                        ImageStorage::delete($oldSignatory->signature_image);
                    }
                }

                if ($signatoryId) {
                    $signatory = $certificate->signatories()->updateOrCreate(
                        ['id' => $signatoryId],
                        $updateData
                    );
                } else {
                    $signatory = $certificate->signatories()->create($updateData);
                }
                
                $submittedSignatoryIds[] = $signatory->id;
            }
        }

        $toDelete = array_diff($existingSignatoryIds, $submittedSignatoryIds);
        if (!empty($toDelete)) {
            $signatoriesToDelete = CertificateSignatory::whereIn('id', $toDelete)->get();
            foreach ($signatoriesToDelete as $sig) {
                ImageStorage::delete($sig->signature_image);
                $sig->delete();
            }
        }

        return redirect()->route('admin.events.certificates.index', $event)->with('success', 'Certificate template saved successfully.');
    }

    public function destroy(Event $event, Certificate $certificate)
    {
        ImageStorage::delete($certificate->background_image);
        
        foreach ($certificate->signatories as $sig) {
            ImageStorage::delete($sig->signature_image);
        }
        
        $certificate->delete();
        return back()->with('success', 'Certificate deleted successfully.');
    }

    public function preview(Certificate $certificate)
    {
        $certificate->load('signatories');
        $event = $certificate->event;

        $student = (object)[
            'full_name' => 'JUAN DELA CRUZ',
            'program' => 'BS Information Technology'
        ];

        return view('admin.events.certificate.preview', compact('event', 'certificate', 'student'));
    }

    public function download(Certificate $certificate)
    {
        $event = $certificate->event;
        
        if (auth()->guard('student')->check()) {
            $student = auth()->guard('student')->user()->student;
            
            // Check if student is awarded this specific certificate
            $isEligible = $student->certificates()->where('certificate_id', $certificate->id)->exists();

            if (!$isEligible) {
                return back()->with('error', 'You are not eligible for this certificate.');
            }
        } else {
            return back()->with('error', 'Unauthorized.');
        }

        return view('admin.events.certificate.preview', compact('event', 'certificate', 'student'));
    }

    public function updateEligibility(Request $request, Event $event, Student $student)
    {
        $certificateIds = $request->input('certificate_ids', []);
        
        // Only consider certificates belonging to this event
        $eventCertificateIds = $event->certificates()->pluck('id')->toArray();
        $validCertificateIds = array_intersect($certificateIds, $eventCertificateIds);
        
        // Determine newly awarded certificates for this event
        $currentEventCertIds = $student->certificates()
            ->whereIn('certificate_id', $eventCertificateIds)
            ->pluck('certificates.id')
            ->toArray();
        $newlyAwardedIds = array_values(array_diff($validCertificateIds, $currentEventCertIds));

        // Get student's current certificates NOT in this event
        $otherEventCertificates = $student->certificates()
            ->whereNotIn('certificate_id', $eventCertificateIds)
            ->pluck('certificates.id')
            ->toArray();
            
        // Sync the combination
        $student->certificates()->sync(array_merge($otherEventCertificates, $validCertificateIds));

        // Send notification if any new awards were granted
        if (!empty($newlyAwardedIds) && $student->user && $student->user->email) {
            $awardedCertificates = Certificate::whereIn('id', $newlyAwardedIds)->get()->all();
            Messenger::send($student->user->email, new CertificateAwardedMail($student, $event, $awardedCertificates));
        }

        return response()->json(['success' => true]);
    }

    public function bulkEligibility(Request $request, Event $event)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'certificate_ids' => 'nullable|array',
            'certificate_ids.*' => 'exists:certificates,id',
            'action' => 'required|in:award,revoke'
        ]);

        $students = Student::whereIn('id', $request->student_ids)->get();
        $certificateIds = $request->input('certificate_ids', []);

        foreach ($students as $student) {
            if ($request->action === 'award') {
                // Compute newly awarded for this student
                $newlyAwardedIds = [];
                if (!empty($certificateIds)) {
                    $already = $student->certificates()
                        ->whereIn('certificate_id', $certificateIds)
                        ->pluck('certificates.id')
                        ->toArray();
                    $newlyAwardedIds = array_values(array_diff($certificateIds, $already));
                }

                $student->certificates()->syncWithoutDetaching($certificateIds);

                if (!empty($newlyAwardedIds) && $student->user && $student->user->email) {
                    $awardedCertificates = Certificate::whereIn('id', $newlyAwardedIds)->get()->all();
                    Messenger::send($student->user->email, new CertificateAwardedMail($student, $event, $awardedCertificates));
                }
            } else {
                $student->certificates()->detach($certificateIds);
            }
        }

        return back()->with('success', 'Selected participants eligibility updated successfully.');
    }
}
