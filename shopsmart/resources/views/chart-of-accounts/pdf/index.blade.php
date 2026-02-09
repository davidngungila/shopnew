<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Chart of Accounts - {{ config('app.name', 'TmcsSmart') }}</title>
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
        th.text-center {
            text-align: center;
        }
        td {
            padding: 6px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .section-header {
            background: #009245;
            color: white;
            font-weight: bold;
        }
        .inactive {
            color: #9ca3af;
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
        <div class="title">CHART OF ACCOUNTS</div>
        <div class="header-info">
            Generated: {{ now()->format('Y-m-d H:i:s') }}<br>
            Company: {{ config('app.name', 'TmcsSmart') }}
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Total Accounts:</div>
            <div class="stats-cell">{{ number_format($totalAccounts ?? 0, 0) }}</div>
            <div class="stats-cell stats-label">Total Balance:</div>
            <div class="stats-cell"><strong>TZS {{ number_format($totalBalance ?? 0, 0) }}</strong></div>
        </div>
    </div>

    <!-- Accounts Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Code</th>
                <th style="width: 25%;">Account Name</th>
                <th style="width: 12%;">Type</th>
                <th style="width: 15%;">Category</th>
                <th class="text-right" style="width: 15%;">Balance (TZS)</th>
                <th class="text-center" style="width: 10%;">Status</th>
                <th style="width: 13%;">Description</th>
            </tr>
        </thead>
        <tbody>
            @php
                $currentType = '';
            @endphp
            @foreach($accounts ?? [] as $account)
                @if($currentType !== $account->account_type)
                    @php
                        $currentType = $account->account_type;
                    @endphp
                    <tr class="section-header">
                        <td colspan="7" style="padding: 8px; text-transform: uppercase;">{{ ucfirst($account->account_type) }} Accounts</td>
                    </tr>
                @endif
                <tr class="{{ !$account->is_active ? 'inactive' : '' }}">
                    <td>{{ $account->account_code }}</td>
                    <td>{{ $account->account_name }}</td>
                    <td>{{ ucfirst($account->account_type) }}</td>
                    <td>{{ $account->account_category ?? '-' }}</td>
                    <td class="text-right" style="font-weight: 600;">{{ number_format($account->current_balance ?? 0, 0) }}</td>
                    <td class="text-center">
                        @if($account->is_active)
                            <span style="color: #059669;">Active</span>
                        @else
                            <span style="color: #9ca3af;">Inactive</span>
                        @endif
                    </td>
                    <td>{{ \Illuminate\Support\Str::limit($account->description ?? '-', 30) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p><strong>FeedTan Community Microfinance Group - Chart of Accounts</strong></p>
        <p>Report generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
        <p>Total Records: {{ $accounts->count() ?? 0 }}</p>
    </div>
</body>
</html>
