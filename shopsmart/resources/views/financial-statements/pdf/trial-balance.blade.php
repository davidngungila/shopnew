<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Trial Balance - {{ config('app.name', 'TmcsSmart') }}</title>
    <style>
        @page {
            margin: 10mm 12mm;
            size: A4 landscape;
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
        <div class="title">TRIAL BALANCE</div>
        <div class="header-info">
            As of: <strong>{{ \Carbon\Carbon::parse($asOfDate)->format('F d, Y') }}</strong><br>
            Generated: {{ now()->format('Y-m-d H:i:s') }}<br>
            Company: {{ config('app.name', 'TmcsSmart') }}
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Total Debit:</div>
            <div class="stats-cell"><strong>{{ number_format($totalDebit ?? 0, 0) }} TSh</strong></div>
            <div class="stats-cell stats-label">Total Credit:</div>
            <div class="stats-cell"><strong>{{ number_format($totalCredit ?? 0, 0) }} TSh</strong></div>
            <div class="stats-cell stats-label">Difference:</div>
            <div class="stats-cell" style="color: {{ abs(($totalDebit ?? 0) - ($totalCredit ?? 0)) < 0.01 ? '#059669' : '#dc2626' }}; font-weight: 700;">
                {{ number_format(abs(($totalDebit ?? 0) - ($totalCredit ?? 0)), 0) }} TSh
            </div>
        </div>
    </div>

    <!-- Trial Balance Table -->
    <div class="section">
        <div class="section-header">Trial Balance</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th style="width: 10%;">Account Code</th>
                        <th style="width: 40%;">Account Name</th>
                        <th style="width: 15%;">Type</th>
                        <th class="text-right" style="width: 17.5%;">Debit (TSh)</th>
                        <th class="text-right" style="width: 17.5%;">Credit (TSh)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $currentType = '';
                    @endphp
                    @foreach($accounts ?? [] as $account)
                        @if($currentType !== $account['type'])
                            @php
                                $currentType = $account['type'];
                            @endphp
                            <tr style="background: #f3f4f6; font-weight: 700;">
                                <td colspan="5" style="padding: 8px; text-transform: uppercase;">{{ ucfirst($account['type']) }} Accounts</td>
                            </tr>
                        @endif
                        <tr>
                            <td>{{ $account['code'] }}</td>
                            <td>{{ $account['name'] }}</td>
                            <td>{{ ucfirst($account['type']) }}</td>
                            <td class="text-right" style="font-weight: 600;">{{ ($account['debit'] ?? 0) > 0 ? number_format($account['debit'], 0) : '-' }}</td>
                            <td class="text-right" style="font-weight: 600;">{{ ($account['credit'] ?? 0) > 0 ? number_format($account['credit'], 0) : '-' }}</td>
                        </tr>
                    @endforeach
                    <tr style="background: #009245; color: white; font-weight: 700; border-top: 3px solid #009245;">
                        <td colspan="3"><strong>TOTAL</strong></td>
                        <td class="text-right"><strong>{{ number_format($totalDebit ?? 0, 0) }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($totalCredit ?? 0, 0) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer">
        <p><strong>FeedTan Community Microfinance Group - Trial Balance</strong></p>
        <p>Report generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
        <p>As of: {{ \Carbon\Carbon::parse($asOfDate)->format('F d, Y') }}</p>
    </div>
</body>
</html>
