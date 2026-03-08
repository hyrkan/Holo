<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Awarded</title>
</head>
<body style="margin:0;padding:0;background:#f7f7f9;font-family:Arial,Helvetica,sans-serif;color:#111827;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f7f7f9;">
        <tr>
            <td align="center" style="padding:24px;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:600px;background:#ffffff;border:1px solid #e5e7eb;border-radius:8px;">
                    <tr>
                        <td style="padding:24px;">
                            <h2 style="margin:0 0 12px 0;font-size:20px;line-height:1.4;">Congratulations {{ $student->first_name }}!</h2>
                            <p style="margin:0 0 16px 0;font-size:14px;line-height:1.6;">
                                You have been awarded the following certificate(s) for <strong>{{ $event->name }}</strong>:
                            </p>
                            <ul style="margin:0 0 16px 24px;padding:0;font-size:14px;line-height:1.6;">
                                @foreach ($certificates as $certificate)
                                    <li>{{ $certificate->name ?? $certificate->title }}</li>
                                @endforeach
                            </ul>
                            <p style="margin:0 0 20px 0;font-size:14px;line-height:1.6;">
                                You can now download your certificate(s) from your account. Log in and go to <strong>Events Joined</strong> to access and download your certificates.
                            </p>
                            <p style="margin:0;font-size:12px;color:#6b7280;line-height:1.6;">
                                This is an automated message from {{ config('app.name') }}.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
