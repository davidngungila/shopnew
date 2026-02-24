<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Profit & Loss Statement - {{ config('app.name', 'TmcsSmart') }}</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 8pt;
        }
        th {
            background: #009245;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #009245;
        }
        th.text-right {
            text-align: right;
        }
        td {
            padding: 6px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
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
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 9pt;
        }
        .summary-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .summary-table td:first-child {
            font-weight: 600;
            background: #f9fafb;
            width: 70%;
        }
        .summary-table td:last-child {
            text-align: right;
            font-weight: 600;
        }
        .summary-table tr.total td {
            background: #009245;
            color: white;
            font-size: 11pt;
            font-weight: bold;
            border: 1px solid #009245;
        }
        .summary-table tr.total td:first-child {
            background: #009245;
            color: white;
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
        <div class="title">PROFIT & LOSS STATEMENT</div>
        <div class="header-info">
            Period: {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}<br>
            Generated: {{ now()->format('Y-m-d H:i:s') }}<br>
            Company: {{ config('app.name', 'TmcsSmart') }}
        </div>
    </div>

    <!-- Profit & Loss Statement -->
    <div class="section">
        <div class="section-header">Statement</div>
        <div class="section-content">
            <table class="summary-table">
                <tr style="background: #f3f4f6;">
                    <td><strong>REVENUE</strong></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="padding-left: 20px;">Sales Revenue</td>
                    <td>{{ number_format($revenue ?? 0, 0) }} TSh</td>
                </tr>
                <tr style="background: #f3f4f6; border-top: 2px solid #009245;">
                    <td><strong>Total Revenue</strong></td>
                    <td><strong>{{ number_format($revenue ?? 0, 0) }} TSh</strong></td>
                </tr>

                <tr style="background: #f3f4f6; margin-top: 10px;">
                    <td><strong>COST OF GOODS SOLD</strong></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="padding-left: 20px;">Purchases</td>
                    <td>{{ number_format($cogs ?? 0, 0) }} TSh</td>
                </tr>
                <tr style="background: #f3f4f6; border-top: 2px solid #009245;">
                    <td><strong>Total COGS</strong></td>
                    <td><strong>{{ number_format($cogs ?? 0, 0) }} TSh</strong></td>
                </tr>

                <tr style="background: #e6f5ed; border-top: 3px solid #009245; margin-top: 15px;">
                    <td><strong>GROSS PROFIT</strong></td>
                    <td style="color: {{ ($grossProfit ?? 0) >= 0 ? '#059669' : '#dc2626' }};"><strong>{{ number_format($grossProfit ?? 0, 0) }} TSh</strong></td>
                </tr>

                <tr style="background: #f3f4f6; margin-top: 10px;">
                    <td><strong>OPERATING EXPENSES</strong></td>
                    <td></td>
                </tr>
                @forelse($expenseBreakdown ?? [] as $expense)
                <tr>
                    <td style="padding-left: 20px;">{{ $expense->category }}</td>
                    <td>{{ number_format($expense->total ?? 0, 0) }} TSh</td>
                </tr>
                @empty
                <tr>
                    <td style="padding-left: 20px;">No expenses recorded</td>
                    <td>0 TSh</td>
                </tr>
                @endforelse
                <tr style="background: #f3f4f6; border-top: 2px solid #009245;">
                    <td><strong>Total Operating Expenses</strong></td>
                    <td><strong>{{ number_format($operatingExpenses ?? 0, 0) }} TSh</strong></td>
                </tr>

                <tr class="total">
                    <td><strong>NET PROFIT / (LOSS)</strong></td>
                    <td style="color: {{ ($netProfit ?? 0) >= 0 ? '#ffffff' : '#fee2e2' }};"><strong>{{ number_format($netProfit ?? 0, 0) }} TSh</strong></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="footer">
        <p><strong>FeedTan Community Microfinance Group - Profit & Loss Statement</strong></p>
        <p>Report generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
        <p>Period: {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}</p>
    </div>
</body>
</html>
