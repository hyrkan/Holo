<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Verification - Holo Board</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme.min.css') }}" />
    <style>
        body { 
            background-color: #f4f7fa; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            min-height: 100vh; 
            padding: 20px;
            font-family: 'Inter', sans-serif;
        }
        .verify-card { 
            max-width: 550px; 
            width: 100%; 
            border: none; 
            border-radius: 20px; 
            box-shadow: 0 15px 35px rgba(71, 0, 200, 0.1); 
            overflow: hidden; 
            background: white; 
        }
        .verify-header { 
            background: #4700c8; 
            color: white; 
            padding: 40px 30px; 
            text-align: center; 
            position: relative;
        }
        .verify-header::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 0;
            right: 0;
            height: 40px;
            background: white;
            border-radius: 50% 50% 0 0;
        }
        .verify-body { 
            padding: 20px 40px 45px; 
        }
        .check-badge { 
            width: 90px;
            height: 90px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: -65px auto 20px;
            position: relative;
            z-index: 10;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 4px;
        }
        .check-badge img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ecfdf5;
        }
        .student-name { 
            font-size: 26px; 
            font-weight: 800; 
            color: #1e293b; 
            margin-bottom: 5px; 
            letter-spacing: -0.5px;
        }
        .certificate-title { 
            color: #4700c8; 
            font-weight: 600; 
            font-size: 16px;
            margin-bottom: 30px; 
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .info-container {
            background: #f8fafc;
            border-radius: 15px;
            padding: 10px 20px;
            margin-bottom: 25px;
        }
        .info-row { 
            display: flex; 
            flex-direction: column;
            padding: 15px 0; 
        }
        .info-row:not(:last-child) {
            border-bottom: 1px solid #e2e8f0;
        }
        .info-label { 
            color: #94a3b8; 
            font-size: 11px; 
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
            font-weight: 700;
        }
        .info-value { 
            color: #334155; 
            font-weight: 600; 
            font-size: 15px; 
        }
        .footer-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            opacity: 0.6;
        }
        .footer-logo img {
            height: 24px;
        }
        .footer-logo span {
            font-weight: 700;
            color: #1e293b;
        }
    </style>
</head>
<body>
    <div class="verify-card text-center">
        <div class="verify-header">
            <h4 class="mb-1 fw-bold">Certificate Verified</h4>
            <p class="mb-0 opacity-75 small">This document is officially recognized</p>
        </div>
        <div class="verify-body">
            <div class="check-badge">
                @if($student->face_photo_url)
                    <img src="{{ $student->face_photo_url }}" alt="{{ $student->full_name }}">
                @else
                    <div style="width: 100%; height: 100%; border-radius: 50%; background-color: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 32px; color: #94a3b8; font-weight: bold;">
                        {{ substr($student->first_name ?? $student->full_name, 0, 1) }}
                    </div>
                @endif
            </div>
            
            <div class="student-name">{{ $student->full_name }}</div>
            <div class="certificate-title">{{ $certificate->title }}</div>
            
            <div class="info-container text-start">
                <div class="info-row">
                    <span class="info-label">Event Details</span>
                    <span class="info-value">{{ $certificate->event->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Awarded On</span>
                    <span class="info-value">{{ $awardedAt->format('F d, Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Verification ID</span>
                    <span class="info-value text-muted font-monospace" style="font-size: 12px;">{{ $token }}</span>
                </div>
            </div>
            
            <div class="footer-logo">
                <img src="{{ asset('landing/img/logo.jpg') }}" alt="Holo Board">
                <span>Holo Board</span>
            </div>

            <div class="mt-4 pt-3 border-top">
                <a href="{{ url('/') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-4">
                    Visit Holo Board
                </a>
            </div>
        </div>
    </div>

    <!-- Background icons or shapes for premium feel -->
    <script src="{{ asset('assets/vendors/js/vendors.min.js') }}"></script>
</body>
</html>
