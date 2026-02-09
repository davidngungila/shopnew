<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Balance Sheet - {{ config('app.name', 'TmcsSmart') }}</title>
    <style>
        @page {
            margin: 10mm 12mm;
            size: A4;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.4;
            color: #333;
        }
        .header {
            border-bottom: 3px solid #009245;
            padding-bottom: 15px;
            margin-bottom: 15px;
            text-align: center;
            width: 100%;
        }
        .header-image {
            width: 100%;
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto 15px auto;
        }
        .title {
            font-size: 18pt;
            font-weight: bold;
            color: #009245;
            margin: 15px 0 10px 0;
        }
        .header-info {
            font-size: 10pt;
            color: #666;
            margin-top: 8px;
        }
        .stats {
            display: table;
            width: 100%;
            margin: 15px 0;
            border-collapse: collapse;
        }
        .stats-row {
            display: table-row;
        }
        .stats-cell {
            display: table-cell;
            padding: 8px;
            border: 1px solid #ddd;
            background: #f9f9f9;
            font-size: 8pt;
        }
        .stats-label {
            font-weight: bold;
            color: #009245;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            background: white;
        }
        .info-table tr {
            border-bottom: 1px solid #e5e7eb;
        }
        .info-table tr:last-child {
            border-bottom: none;
        }
        .info-table td {
            padding: 5px 8px;
            vertical-align: top;
            font-size: 8.5pt;
        }
        .info-table td:first-child {
            font-weight: 600;
            width: 60%;
            color: #374151;
            background: #f9fafb;
            border-right: 1px solid #e5e7eb;
        }
        .info-table td:last-child {
            color: #1a1a1a;
            text-align: right;
            font-weight: 600;
        }
        .two-column {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .column {
            display: table-cell;
            width: 50%;
            padding: 0 10px;
            vertical-align: top;
        }
        .column:first-child {
            padding-left: 0;
        }
        .column:last-child {
            padding-right: 0;
        }
        .section {
            margin: 15px 0;
            page-break-inside: avoid;
        }
        .section-header {
            background: #009245;
            color: white;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 10pt;
            margin-bottom: 8px;
        }
        .section-content {
            padding: 8px 0;
            font-size: 8.5pt;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 7pt;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div style="text-align: center; margin-bottom: 15px;">
            @php
                $headerImagePath = public_path('header-mfumo.png');
                $headerBase64 = '';
                if (file_exists($headerImagePath)) {
                    $headerImageData = file_get_contents($headerImagePath);
                    $headerBase64 = 'data:image/png;base64,' . base64_encode($headerImageData);
                }
            @endphp
            @if($headerBase64)
            <img src="{{ $headerBase64 }}" alt="FeedTan Header" class="header-image">
            @endif
        </div>
        <div class="title">BALANCE SHEET</div>
        <div class="header-info">
            As of: <strong>{{ \Carbon\Carbon::parse($asOfDate)->format('F d, Y') }}</strong><br>
            Generated: {{ now()->format('Y-m-d H:i:s') }}<br>
            Company: {{ config('app.name', 'TmcsSmart') }}
        </div>
    </div>

    <!-- Balance Sheet -->
    <div class="two-column">
        <div class="column">
            <div class="section">
                <div class="section-header">ASSETS</div>
                <div class="section-content">
                    <table class="info-table">
                        <tr>
                            <td>Current Assets</td>
                            <td>{{ number_format($currentAssets ?? 0, 0) }} TZS</td>
                        </tr>
                        <tr>
                            <td>Fixed Assets</td>
                            <td>{{ number_format($fixedAssets ?? 0, 0) }} TZS</td>
                        </tr>
                        <tr style="background: #f3f4f6; border-top: 2px solid #009245; font-weight: 700;">
                            <td>Total Assets</td>
                            <td style="font-size: 10pt;">{{ number_format($totalAssets ?? 0, 0) }} TZS</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="section">
                <div class="section-header">LIABILITIES & EQUITY</div>
                <div class="section-content">
                    <table class="info-table">
                        <tr>
                            <td>Current Liabilities</td>
                            <td>{{ number_format($currentLiabilities ?? 0, 0) }} TZS</td>
                        </tr>
                        <tr>
                            <td>Long-term Liabilities</td>
                            <td>{{ number_format($longTermLiabilities ?? 0, 0) }} TZS</td>
                        </tr>
                        <tr style="background: #f3f4f6; border-top: 2px solid #009245; font-weight: 700;">
                            <td>Total Liabilities</td>
                            <td style="font-size: 10pt;">{{ number_format($totalLiabilities ?? 0, 0) }} TZS</td>
                        </tr>
                        <tr>
                            <td>Capital</td>
                            <td>{{ number_format($capital ?? 0, 0) }} TZS</td>
                        </tr>
                        <tr>
                            <td>Retained Earnings</td>
                            <td>{{ number_format($retainedEarnings ?? 0, 0) }} TZS</td>
                        </tr>
                        <tr style="background: #f3f4f6; border-top: 2px solid #009245; font-weight: 700;">
                            <td>Total Equity</td>
                            <td style="font-size: 10pt;">{{ number_format($totalEquity ?? 0, 0) }} TZS</td>
                        </tr>
                        <tr style="background: #e6f5ed; border-top: 3px solid #009245; font-weight: 700; font-size: 10pt;">
                            <td>Total Liabilities & Equity</td>
                            <td>{{ number_format(($totalLiabilities ?? 0) + ($totalEquity ?? 0), 0) }} TZS</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p><strong>FeedTan Community Microfinance Group - Balance Sheet</strong></p>
        <p>Report generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
        <p>As of: {{ \Carbon\Carbon::parse($asOfDate)->format('F d, Y') }}</p>
    </div>
</body>
</html>
