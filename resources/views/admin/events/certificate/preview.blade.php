<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate - {{ $student->full_name }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Georgia', serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .certificate-container {
            width: 297mm;
            height: 210mm;
            background-color: white;
            position: relative;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 40px;
            box-sizing: border-box;
            @if($certificate->background_image)
                background-image: url("{{ $certificate->background_image_url }}");
                background-size: cover;
                background-position: center;
            @endif
        }
        
        @if(!$certificate->background_image)
        .certificate-container::before {
            content: "";
            position: absolute;
            top: 20px;
            bottom: 20px;
            left: 20px;
            right: 20px;
            border: 10px double #1a237e;
        }
        @endif

        .content {
            position: relative;
            z-index: 10;
            width: 85%;
        }

        .title {
            font-size: 48px;
            font-weight: bold;
            color: #1a237e;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .subtitle {
            font-size: 24px;
            color: #333;
            font-style: italic;
            margin-bottom: 25px;
        }

        .student-name {
            font-size: 56px;
            color: #1a237e;
            font-style: normal;
            margin-bottom: 25px;
            border-bottom: 2px solid #1a237e;
            display: inline-block;
            padding: 0 20px;
            font-family: 'Times New Roman', serif;
        }

        .body-text {
            font-size: 18px;
            color: #444;
            line-height: 1.6;
            margin-bottom: 50px;
        }

        .signatories {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
            margin-top: 20px;
        }

        .signatory-column {
            padding: 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        /* 1 Signatory: Col-12, Align Left */
        .sig-count-1 .signatory-column {
            width: 100%;
            align-items: flex-start;
            text-align: left;
        }

        /* 2 Signatories: Col-6, Centered */
        .sig-count-2 .signatory-column {
            width: 50%;
            align-items: center;
            text-align: center;
        }

        /* 3+ Signatories: Col-4, Centered */
        .sig-count-3 .signatory-column,
        .sig-count-more .signatory-column {
            width: 33.33%;
            align-items: center;
            text-align: center;
        }

        .signatory-wrapper {
            display: inline-flex;
            flex-direction: column;
            align-items: inherit;
            min-width: 250px;
        }

        .signature-img {
            max-height: 70px;
            margin-bottom: -15px;
            position: relative;
            z-index: 5;
        }

        .signature-line {
            border-top: 1px solid #333;
            width: 100%;
            padding-top: 5px;
            font-weight: bold;
            font-size: 18px;
            position: relative;
            z-index: 10;
        }

        .signatory-label {
            font-size: 14px;
            color: #666;
            font-style: italic;
        }

        @media print {
            body {
                background-color: white;
            }
            .certificate-container {
                box-shadow: none;
                margin: 0;
            }
            .print-btn {
                display: none;
            }
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #1a237e;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1000;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .print-btn:hover {
            background-color: #283593;
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
        Download/Print PDF
    </button>

    <div class="certificate-container">
        <div class="content">
            <div class="title">{{ $certificate->title }}</div>
            <div class="subtitle">{{ $certificate->sub_title }}</div>
            <div class="student-name">{{ $student->full_name }}</div>
            <div class="body-text">{{ $certificate->body }}</div>

            @php
                $sigCount = $certificate->signatories->count();
                $countClass = $sigCount == 1 ? 'sig-count-1' : ($sigCount == 2 ? 'sig-count-2' : ($sigCount == 3 ? 'sig-count-3' : 'sig-count-more'));
            @endphp

            <div class="signatories {{ $countClass }}">
                @foreach($certificate->signatories as $signatory)
                    <div class="signatory-column">
                        <div class="signatory-wrapper">
                            @if($signatory->signature_image)
                                <img src="{{ $signatory->signature_url }}" class="signature-img" alt="Signature">
                            @else
                                <div style="height: 55px;"></div>
                            @endif
                            <div class="signature-line">{{ $signatory->name }}</div>
                            <div class="signatory-label">{{ $signatory->label }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>
