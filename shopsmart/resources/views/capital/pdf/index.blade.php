<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Capital Report - {{ config('app.name', 'TmcsSmart') }}</title>
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
        <div class="title">CAPITAL REPORT</div>
        <div class="header-info">
            Generated: {{ now()->format('Y-m-d H:i:s') }}<br>
            Company: {{ config('app.name', 'TmcsSmart') }}
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Total Contributions:</div>
            <div class="stats-cell" style="color: #059669; font-weight: 600;">TZS {{ number_format($totalContributions ?? 0, 0) }}</div>
            <div class="stats-cell stats-label">Total Withdrawals:</div>
            <div class="stats-cell" style="color: #dc2626; font-weight: 600;">TZS {{ number_format($totalWithdrawals ?? 0, 0) }}</div>
            <div class="stats-cell stats-label">Net Capital:</div>
            <div class="stats-cell" style="color: {{ ($netCapital ?? 0) >= 0 ? '#059669' : '#dc2626' }}; font-weight: 700;">TZS {{ number_format($netCapital ?? 0, 0) }}</div>
        </div>
    </div>

    <!-- Capital Transactions Table -->
    <div class="section">
        <div class="section-header">Capital Transactions</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th style="width: 15%;">Transaction #</th>
                        <th style="width: 12%;">Date</th>
                        <th style="width: 15%;">Type</th>
                        <th style="width: 30%;">Description</th>
                        <th style="width: 15%;">Reference</th>
                        <th class="text-right" style="width: 13%;">Amount (TZS)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($capitals ?? [] as $capital)
                    <tr>
                        <td>{{ $capital->transaction_number }}</td>
                        <td>{{ \Carbon\Carbon::parse($capital->transaction_date)->format('M d, Y') }}</td>
                        <td>{{ ucfirst($capital->type) }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($capital->description ?? '-', 40) }}</td>
                        <td>{{ $capital->reference ?? '-' }}</td>
                        <td class="text-right" style="font-weight: 600; color: {{ in_array($capital->type, ['contribution', 'profit']) ? '#059669' : '#dc2626' }};">
                            {{ number_format($capital->amount ?? 0, 0) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px; color: #9ca3af;">No capital transactions found</td>
                    </tr>
                    @endforelse
                    <tr style="background: #f3f4f6; font-weight: 700; border-top: 2px solid #009245;">
                        <td colspan="5"><strong>NET CAPITAL</strong></td>
                        <td class="text-right" style="font-weight: 700; color: {{ ($netCapital ?? 0) >= 0 ? '#059669' : '#dc2626' }};">
                            <strong>TZS {{ number_format($netCapital ?? 0, 0) }}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer">
        <p><strong>FeedTan Community Microfinance Group - Capital Report</strong></p>
        <p>Report generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
        <p>Total Records: {{ $capitals->count() ?? 0 }}</p>
    </div>
</body>
</html>
