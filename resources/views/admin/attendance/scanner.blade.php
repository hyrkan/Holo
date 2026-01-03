@extends('layouts.admin')

@section('title', 'QR Scanner || Holo Board')

@push('styles')
<style>
    #reader {
        width: 100%;
        border-radius: 15px;
        overflow: hidden;
        border: none !important;
    }
    #reader__dashboard {
        padding: 20px !important;
    }
    .scanner-container {
        max-width: 600px;
        margin: 0 auto;
    }
    .scan-feedback {
        transition: all 0.3s ease;
    }
    .student-card {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 15px;
        border: 1px solid #e9ecef;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush

@section('content')
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1">
                            @if($event)
                                Smart Check-in: {{ $event->name }}
                            @else
                                Universal Student Lookup
                            @endif
                        </h5>
                        <p class="text-muted small mb-0">
                            @if($event)
                                Automatically recording attendance for today: {{ date('M d, Y') }}
                            @else
                                Scan any student QR to view their records and profile.
                            @endif
                        </p>
                    </div>
                    @if($event)
                        <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-light btn-sm">
                            <i class="feather-arrow-left me-2"></i> Back to Event
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="scanner-container">
                        <div id="reader"></div>
                        
                        <!-- Manual Entry Fallback -->
                        <div class="mt-3 p-3 border rounded-4 bg-light text-center" id="manual-entry-container">
                            <p class="small text-muted mb-2">Camera issues? Use manual entry:</p>
                            <div class="input-group">
                                <input type="text" id="manual-uuid" class="form-control" placeholder="Enter Student UUID...">
                                <button class="btn btn-primary" type="button" id="btn-manual-scan">
                                    <i class="feather-search"></i>
                                </button>
                            </div>
                            <div class="mt-2 text-start">
                                <small class="text-info"><i class="feather-help-circle me-1"></i> Tip: Use HTTPS or 'herd secure' to enable camera scanning.</small>
                            </div>
                        </div>

                        <div id="scan-result" class="mt-4 scan-feedback d-none">
                            <div class="alert d-flex align-items-start mb-0" role="alert" id="result-alert">
                                <div class="scan-icon me-3 mt-1">
                                    <i class="feather-check-circle fs-2" id="result-icon"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="alert-heading mb-1 fw-bold" id="result-title">Success!</h6>
                                    <p class="mb-2" id="result-message"></p>
                                    
                                    <div class="student-card shadow-sm border-0 bg-white p-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="avatar-text bg-soft-primary text-primary rounded-circle me-2" style="width: 32px; height: 32px; font-size: 12px; display: flex; align-items: center; justify-content: center;">
                                                <i class="feather-user"></i>
                                            </div>
                                            <h6 class="mb-0" id="res-name"></h6>
                                        </div>
                                        <div class="row g-2 small text-muted">
                                            <div class="col-6">ID: <span class="text-dark fw-medium" id="res-id"></span></div>
                                            <div class="col-6">Year: <span class="text-dark fw-medium" id="res-year"></span></div>
                                            <div class="col-12">Program: <span class="text-dark fw-medium" id="res-program"></span></div>
                                        </div>
                                        <hr class="my-2 opacity-10">
                                        <a href="#" id="res-profile-link" class="btn btn-sm btn-soft-primary w-100 py-1" target="_blank">View Full Profile</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fs-13 fw-bold text-uppercase mb-0">Recent Activity</h6>
                                <span class="badge bg-light text-dark" id="scan-count">0 Scans Today</span>
                            </div>
                            <div id="recent-scans" class="list-group list-group-flush border rounded-4 overflow-hidden">
                                <div class="list-group-item text-center py-4 text-muted" id="no-scans">
                                    <i class="feather-camera d-block fs-3 mb-2"></i>
                                    Ready to scan QR codes
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    let html5QrcodeScanner = null;
    let isProcessing = false;
    let totalScans = 0;
    let lastScannedUuid = null;
    let lastScannedTime = 0;

    function onScanSuccess(decodedText) {
        if (isProcessing) return;
        
        // Prevent duplicate scan of the same QR within 5 seconds for same student
        const now = Date.now();
        if (decodedText === lastScannedUuid && (now - lastScannedTime) < 5000) {
            return;
        }

        isProcessing = true;
        lastScannedUuid = decodedText;
        lastScannedTime = now;
        
        html5QrcodeScanner.pause();
        processAttendance(decodedText);
    }

    function processAttendance(uuid) {
        const resultDiv = document.getElementById('scan-result');
        const alertBox = document.getElementById('result-alert');
        const icon = document.getElementById('result-icon');
        const title = document.getElementById('result-title');
        const message = document.getElementById('result-message');
        
        fetch("{{ route('admin.attendance.scan') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                student_uuid: uuid,
                event_id: "{{ $event ? $event->id : '' }}"
            })
        })
        .then(response => response.json())
        .then(data => {
            resultDiv.classList.remove('d-none');
            
            // Fill student info
            document.getElementById('res-name').innerText = data.student.name;
            document.getElementById('res-id').innerText = data.student.student_number;
            document.getElementById('res-year').innerText = data.student.year_level;
            document.getElementById('res-program').innerText = data.student.program;
            document.getElementById('res-profile-link').href = data.student.profile_url;

            if (data.success) {
                toastr.success(data.message, data.student.name);
                alertBox.className = 'alert alert-success d-flex align-items-start mb-0';
                icon.className = 'feather-check-circle fs-2';
                title.innerText = 'Success!';
                message.innerText = data.message;
                addToRecent(data.student.name, true, data.message);
                totalScans++;
                document.getElementById('scan-count').innerText = `${totalScans} Scans Today`;
            } else {
                if (data.type === 'attendance_exists') {
                    toastr.warning(data.message, data.student.name);
                } else {
                    toastr.error(data.message, 'Scanner Alert');
                }
                alertBox.className = 'alert alert-warning d-flex align-items-start mb-0';
                icon.className = 'feather-alert-triangle fs-2';
                title.innerText = 'Looked Up';
                message.innerText = data.message;
                addToRecent(data.student.name, false, data.message);
            }

            setTimeout(() => {
                isProcessing = false;
                html5QrcodeScanner.resume();
                setTimeout(() => {
                    if (!isProcessing) resultDiv.classList.add('d-none');
                }, 5000);
            }, 2000);
        })
        .catch(error => {
            console.error('Error:', error);
            isProcessing = false;
            html5QrcodeScanner.resume();
        });
    }

    function addToRecent(name, success, info) {
        const container = document.getElementById('recent-scans');
        const noScans = document.getElementById('no-scans');
        if (noScans) noScans.remove();

        const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        const item = document.createElement('div');
        item.className = `list-group-item d-flex justify-content-between align-items-center border-start border-4 ${success ? 'border-success' : 'border-warning'}`;
        item.innerHTML = `
            <div>
                <h6 class="mb-0 fs-13 text-truncate" style="max-width: 200px;">${name}</h6>
                <small class="text-muted d-block">${info}</small>
            </div>
            <span class="badge bg-light text-dark fs-10">${time}</span>
        `;
        container.prepend(item);
        if (container.children.length > 5) container.removeChild(container.lastChild);
    }

    $(document).ready(function() {
        // Toastr Configuration
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };
        html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);

        // Manual Entry Logic
        document.getElementById('btn-manual-scan').addEventListener('click', function() {
            const uuid = document.getElementById('manual-uuid').value.trim();
            if (uuid) {
                processAttendance(uuid);
                document.getElementById('manual-uuid').value = '';
            }
        });

        document.getElementById('manual-uuid').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('btn-manual-scan').click();
            }
        });
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@endpush
