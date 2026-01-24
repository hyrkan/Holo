<!DOCTYPE html>
<html>
<head>
    <title>Lost & Found Alert</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .header { background: #f8f9fa; padding: 10px; border-bottom: 2px solid #007bff; margin-bottom: 20px; }
        .footer { margin-top: 30px; font-size: 0.8em; color: #777; }
        .status { font-weight: bold; color: #28a745; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>HoloBoard Lost & Found</h2>
        </div>
        
        <p>Hello,</p>
        
        <p>We are pleased to inform you that your report for the item <strong>"{{ $report->item_name }}"</strong> has been <span class="status">RESOLVED</span>.</p>
        
        <p><strong>Details:</strong></p>
        <ul>
            <li><strong>Item:</strong> {{ $report->item_name }}</li>
            <li><strong>Location:</strong> {{ $report->location }}</li>
            <li><strong>Date Reported:</strong> {{ $report->date_reported->format('M d, Y') }}</li>
            @if($report->returned_by_name)
                <li><strong>Returned By:</strong> {{ $report->returned_by_name }}</li>
            @endif
        </ul>

        <p>You can visit the HoloBoard portal or contact the administration office for more details.</p>
        
        <div class="footer">
            <p>This is an automated notification from HoloBoard.</p>
        </div>
    </div>
</body>
</html>
