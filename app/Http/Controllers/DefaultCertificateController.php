<?php

namespace App\Http\Controllers;

use App\Models\DefaultCertificate;
use App\Models\DefaultCertificateSignatory;
use App\Helpers\ImageStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DefaultCertificateController extends Controller
{
    public function index()
    {
        $certificates = DefaultCertificate::orderBy('created_at', 'desc')->get();
        return view('admin.settings.certificates.index', compact('certificates'));
    }

    public function create()
    {
        $certificate = new DefaultCertificate();
        return view('admin.settings.certificates.edit', compact('certificate'));
    }

    public function store(Request $request)
    {
        return $this->save($request, new DefaultCertificate());
    }

    public function edit(DefaultCertificate $certificate)
    {
        $certificate->load('signatories');
        return view('admin.settings.certificates.edit', compact('certificate'));
    }

    public function update(Request $request, DefaultCertificate $certificate)
    {
        return $this->save($request, $certificate);
    }

    protected function save(Request $request, DefaultCertificate $certificate)
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
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only(['name', 'title', 'sub_title', 'body']);

            if ($request->hasFile('background_image')) {
                ImageStorage::delete($certificate->background_image);
                $data['background_image'] = ImageStorage::upload($request->file('background_image'), 'default_certificates');
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
                            $oldSignatory = DefaultCertificateSignatory::find($signatoryId);
                            if ($oldSignatory) {
                                ImageStorage::delete($oldSignatory->signature_image);
                            }
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
                $signatoriesToDelete = DefaultCertificateSignatory::whereIn('id', $toDelete)->get();
                foreach ($signatoriesToDelete as $sig) {
                    /** @var DefaultCertificateSignatory $sig */
                    ImageStorage::delete($sig->signature_image);
                    $sig->delete();
                }
            }

            DB::commit();
            return redirect()->route('admin.settings.certificates.index')->with('success', 'Default certificate template saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error saving certificate: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(DefaultCertificate $certificate)
    {
        ImageStorage::delete($certificate->background_image);
        
        foreach ($certificate->signatories as $sig) {
            ImageStorage::delete($sig->signature_image);
        }
        
        $certificate->delete();
        return back()->with('success', 'Default certificate deleted successfully.');
    }

    public function select(DefaultCertificate $certificate)
    {
        // Deactivate all others
        DefaultCertificate::where('id', '!=', $certificate->id)->update(['is_selected' => false]);
        
        // Activate this one
        $certificate->update(['is_selected' => true]);
        
        return back()->with('success', 'Certificate "' . $certificate->name . '" selected as the default.');
    }

    public function preview(DefaultCertificate $certificate)
    {
        $certificate->load('signatories');
        
        $student = (object)[
            'full_name' => 'JUAN DELA CRUZ',
            'program' => 'BS Information Technology'
        ];

        // We use the same preview view as events but without an event context or with a dummy one
        $event = (object)[
            'title' => 'SAMPLE EVENT TITLE',
            'name' => 'SAMPLE EVENT NAME',
            'location' => 'University Auditorium',
            'eventDates' => collect([
                (object)['date' => now()->toDateString()],
                (object)['date' => now()->addDay()->toDateString()]
            ])
        ];

        // Apply placeholders for preview
        $certificate = clone $certificate;
        $dates = $event->eventDates->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('F d, Y'))->toArray();
        $dateString = count($dates) > 1 
            ? implode(', ', array_slice($dates, 0, -1)) . ' and ' . end($dates) 
            : ($dates[0] ?? '');
            
        $certificate->body = str_replace('[STUDENT_NAME]', $student->full_name, $certificate->body);
        $certificate->body = str_replace('[EVENT_NAME]', $event->name, $certificate->body);
        $certificate->body = str_replace('[EVENT_DATE]', $dateString, $certificate->body);
        $certificate->body = str_replace('[EVENT_LOCATION]', $event->location, $certificate->body);
        
        $verificationToken = 'PREVIEW_ONLY';

        return view('admin.events.certificate.preview', compact('event', 'certificate', 'student', 'verificationToken'));
    }
}
