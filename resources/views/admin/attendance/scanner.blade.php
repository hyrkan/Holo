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
        position: relative;
    }
    #countdown-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.7);
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 100;
        border-radius: 15px;
        color: white;
    }
    .countdown-number {
        font-size: 5rem;
        font-weight: bold;
        margin-bottom: 1rem;
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
    .captured-photo-container {
        width: 100px;
        height: 100px;
        border-radius: 10px;
        overflow: hidden;
        border: 2px solid #fff;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        margin-left: 15px;
        flex-shrink: 0;
    }
    .captured-photo-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
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
                            Smart Check-in: {{ $event->name }}
                        </h5>
                        <p class="text-muted small mb-0">
                            Automatically recording attendance for today: {{ date('M d, Y') }}
                        </p>
                    </div>
                    <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-light btn-sm">
                        <i class="feather-arrow-left me-2"></i> Back to Event
                    </a>
                </div>
                <div class="card-body">
                    <div class="scanner-container">
                        <div id="reader"></div>
                        
                        <div id="countdown-overlay">
                            <div id="pre-capture-content" class="text-center">
                                <div class="h4 fw-bold mb-3">Proof of Presence Required</div>
                                <p class="mb-4">Please lower your QR code and look at the camera.</p>
                                <button class="btn btn-primary btn-lg rounded-pill px-4" id="btn-start-countdown">
                                    <i class="feather-camera me-2"></i> Start 5s Timer
                                </button>
                            </div>
                            <div id="countdown-display" class="d-none text-center">
                                <div class="countdown-number" id="countdown-timer">5</div>
                                <div class="h5 fw-bold">Smile! 📸</div>
                            </div>
                        </div>
                        
                        <!-- Manual Entry Fallback -->
                        <div class="mt-3 p-3 border rounded-4 bg-light text-center" id="manual-entry-container">
                            <p class="small text-muted mb-2">Camera issues? Use manual entry:</p>
                            <div class="input-group">
                                <input type="text" id="manual-uuid" class="form-control" placeholder="Enter Student UUID...">
                                <button class="btn btn-primary" type="button" id="btn-manual-scan">
                                    <i class="feather-search"></i>
                                </button>
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
                                    
                                    <div class="d-flex align-items-start">
                                        <div class="student-card shadow-sm border-0 bg-white p-3 flex-grow-1">
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
                                        <div class="captured-photo-container d-none" id="photo-preview-container">
                                            <img src="" id="res-photo" alt="Captured Photo">
                                            <div class="text-center small fw-bold bg-dark text-white py-1" style="font-size: 8px; position: absolute; bottom: 0; width: 100%; opacity: 0.8;">PROOFOF IN</div>
                                        </div>
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
        console.log("QR Scanned:", decodedText);
        if (isProcessing) {
            console.log("Still processing previous scan, ignoring...");
            return;
        }
        
        // Prevent duplicate scan of the same QR within 5 seconds for same student
        const now = Date.now();
        if (decodedText === lastScannedUuid && (now - lastScannedTime) < 5000) {
            console.log("Duplicate scan detected, ignoring...");
            return;
        }

        isProcessing = true;
        lastScannedUuid = decodedText;
        lastScannedTime = now;
        
        console.log("Starting instant check for:", decodedText);
        
        if (html5QrcodeScanner) {
            html5QrcodeScanner.pause();
        }
        
        // Try instant check first (will succeed if it's a clock-out)
        processAttendance(decodedText, null);
    }

    function showPhotoTrigger(uuid) {
        isProcessing = true; // Stay in processing state
        const overlay = document.getElementById('countdown-overlay');
        const content = document.getElementById('pre-capture-content');
        const display = document.getElementById('countdown-display');
        const startBtn = document.getElementById('btn-start-countdown');

        overlay.style.display = 'flex';
        content.classList.remove('d-none');
        display.classList.add('d-none');

        // Clean up previous event listeners if any
        const newBtn = startBtn.cloneNode(true);
        startBtn.parentNode.replaceChild(newBtn, startBtn);

        newBtn.addEventListener('click', function() {
            startCaptureCountdown(uuid);
        });
    }

    function startCaptureCountdown(uuid) {
        console.log("Starting countdown for photo...");
        const content = document.getElementById('pre-capture-content');
        const display = document.getElementById('countdown-display');
        const timerText = document.getElementById('countdown-timer');
        let timeLeft = 5;

        content.classList.add('d-none');
        display.classList.remove('d-none');
        timerText.innerText = timeLeft;

        const countdownInterval = setInterval(() => {
            timeLeft--;
            console.log("Countdown:", timeLeft);
            if (timeLeft > 0) {
                timerText.innerText = timeLeft;
            } else {
                clearInterval(countdownInterval);
                document.getElementById('countdown-overlay').style.display = 'none';
                
                // Now capture and process with photo
                const photo = capturePhoto();
                processAttendance(uuid, photo);
            }
        }, 1000);
    }

    function capturePhoto() {
        console.log("Capturing photo...");
        const video = document.querySelector('#reader video');
        if (!video) {
            console.warn("No video element found to capture photo!");
            return null;
        }

        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        return canvas.toDataURL('image/jpeg');
    }

    function processAttendance(uuid, photo = null) {
        console.log("Processing attendance. UUID:", uuid, "With Photo:", !!photo);
        const resultDiv = document.getElementById('scan-result');
        const alertBox = document.getElementById('result-alert');
        const icon = document.getElementById('result-icon');
        const title = document.getElementById('result-title');
        const message = document.getElementById('result-message');
        
        if (!photo) {
            toastr.info('Verifying status...', 'Processing');
        } else {
            toastr.info('Sending photo to server...', 'Clocking In');
        }
        
        fetch("{{ route('admin.attendance.scan') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                student_uuid: uuid,
                event_id: "{{ $event->id }}",
                photo: photo
            })
        })
        .then(response => {
            console.log("Response received status:", response.status);
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.indexOf("application/json") !== -1) {
                return response.json();
            } else {
                return response.text().then(text => {
                    console.error("Server returned non-JSON response:", text);
                    throw new Error("Server error: " + (text.substring(0, 500) || "Unknown error"));
                });
            }
        })
        .then(data => {
            console.log("Data received:", data);
            
            if (data.type === 'needs_photo') {
                // This is a clock-in, show the manual trigger button
                showPhotoTrigger(uuid);
                return;
            }

            resultDiv.classList.remove('d-none');
            
            const studentName = (data.student && data.student.name) ? data.student.name : 'Unknown Student';
            
            // Fill student info if available
            if (data.student) {
                document.getElementById('res-name').innerText = data.student.name || '---';
                document.getElementById('res-id').innerText = data.student.student_number || '---';
                document.getElementById('res-year').innerText = data.student.year_level || '---';
                document.getElementById('res-program').innerText = data.student.program || '---';
                document.getElementById('res-profile-link').href = data.student.profile_url || '#';
                
                document.getElementById('res-profile-link').parentElement.classList.remove('d-none');
            } else {
                document.getElementById('res-name').innerText = 'Student Not Found';
                document.getElementById('res-profile-link').parentElement.classList.add('d-none');
            }

            // Handle photo display
            const photoPreview = document.getElementById('res-photo');
            const photoContainer = document.getElementById('photo-preview-container');
            if (photo && data.type === 'clock_in_recorded') {
                photoPreview.src = photo;
                photoContainer.classList.remove('d-none');
            } else {
                photoContainer.classList.add('d-none');
            }

            if (data.success) {
                toastr.success(data.message, studentName);
                alertBox.className = 'alert alert-success d-flex align-items-start mb-0';
                icon.className = 'feather-check-circle fs-2';
                title.innerText = data.type === 'clock_out_recorded' ? 'Clocked Out!' : 'Clocked In!';
                message.innerText = data.message;
                addToRecent(studentName, true, data.message);
                
                if (data.type === 'clock_in_recorded' || data.type === 'attendance_recorded') {
                    totalScans++;
                    document.getElementById('scan-count').innerText = `${totalScans} Scans Today`;
                }
            } else {
                if (data.type === 'attendance_exists') {
                    toastr.warning(data.message, studentName);
                } else {
                    toastr.error(data.message, 'Scanner Alert');
                }
                alertBox.className = 'alert alert-warning d-flex align-items-start mb-0';
                icon.className = 'feather-alert-triangle fs-2';
                title.innerText = 'Attention';
                message.innerText = data.message;
                addToRecent(studentName, false, data.message);
            }

            // Reset for next scan
            setTimeout(() => {
                isProcessing = false;
                if (html5QrcodeScanner) {
                    html5QrcodeScanner.resume();
                }
                setTimeout(() => {
                    if (!isProcessing) resultDiv.classList.add('d-none');
                }, 5000);
            }, 2000);
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            toastr.error(error.message || 'An error occurred. Check console for details.', 'Connection Error');
            isProcessing = false;
            if (html5QrcodeScanner) {
                html5QrcodeScanner.resume();
            }
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
