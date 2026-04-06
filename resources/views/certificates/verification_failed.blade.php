<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Failed - Holo Board</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme.min.css') }}" />
    <style>
        body { background-color: #f4f7fa; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; }
        .error-card { max-width: 450px; width: 100%; border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(220, 38, 38, 0.1); background: white; padding: 50px 30px; text-align: center; }
        .error-icon { width: 80px; height: 80px; background: #fee2e2; color: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; margin: 0 auto 25px; }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-icon">
            <i class="feather-alert-triangle"></i>
        </div>
        <h4 class="fw-bold text-danger mb-3">Verification Failed</h4>
        <p class="text-muted mb-4">The certificate token provided is invalid or has been revoked. We cannot verify the authenticity of this document.</p>
        <a href="{{ url('/') }}" class="btn btn-primary rounded-pill px-5">Back to Home</a>
    </div>
</body>
</html>
