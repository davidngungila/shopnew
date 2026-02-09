<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bank Reconciliation Report - {{ config('app.name', 'TmcsSmart') }}</title>
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
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
        }
        .status-reconciled {
            background: #d1fae5;
            color: #065f46;
        }
        .status-discrepancy {
            background: #fee2e2;
            color: #991b1b;
        }
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        .reconciliation-detail {
            margin-top: 10px;
            padding: 10px;
            background: #f9fafb;
            border-left: 4px solid #009245;
            font-size: 7.5pt;
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
        <div class="title">BANK RECONCILIATION REPORT</div>
        <div class="header-info">
            Generated: {{ now()->format('Y-m-d H:i:s') }}<br>
            Company: {{ config('app.name', 'TmcsSmart') }}
        </div>
    </div>

    <!-- Bank Reconciliations Table -->
    <div class="section">
        <div class="section-header">Bank Reconciliations</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th style="width: 15%;">Reconciliation #</th>
                        <th style="width: 20%;">Account</th>
                        <th style="width: 12%;">Statement Date</th>
                        <th class="text-right" style="width: 15%;">Bank Balance (TZS)</th>
                        <th class="text-right" style="width: 15%;">Book Balance (TZS)</th>
                        <th class="text-right" style="width: 15%;">Adjusted Balance (TZS)</th>
                        <th class="text-center" style="width: 8%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reconciliations ?? [] as $reconciliation)
                    <tr>
                        <td>{{ $reconciliation->reconciliation_number }}</td>
                        <td>{{ $reconciliation->account->account_name ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($reconciliation->statement_date)->format('M d, Y') }}</td>
                        <td class="text-right">{{ number_format($reconciliation->bank_balance ?? 0, 0) }}</td>
                        <td class="text-right">{{ number_format($reconciliation->book_balance ?? 0, 0) }}</td>
                        <td class="text-right" style="font-weight: 600;">{{ number_format($reconciliation->adjusted_balance ?? 0, 0) }}</td>
                        <td class="text-center">
                            <span class="status-badge status-{{ $reconciliation->status }}">
                                {{ ucfirst($reconciliation->status) }}
                            </span>
                        </td>
                    </tr>
                    @if($reconciliation->deposits_in_transit || $reconciliation->outstanding_checks || $reconciliation->bank_charges || $reconciliation->interest_earned)
                    <tr>
                        <td colspan="7">
                            <div class="reconciliation-detail">
                                <strong>Details for {{ $reconciliation->reconciliation_number }}:</strong><br>
                                @if($reconciliation->deposits_in_transit)
                                Deposits in Transit: TZS {{ number_format($reconciliation->deposits_in_transit, 0) }} | 
                                @endif
                                @if($reconciliation->outstanding_checks)
                                Outstanding Checks: TZS {{ number_format($reconciliation->outstanding_checks, 0) }} | 
                                @endif
                                @if($reconciliation->bank_charges)
                                Bank Charges: TZS {{ number_format($reconciliation->bank_charges, 0) }} | 
                                @endif
                                @if($reconciliation->interest_earned)
                                Interest Earned: TZS {{ number_format($reconciliation->interest_earned, 0) }}
                                @endif
                                @if($reconciliation->notes)
                                <br><em>Notes: {{ $reconciliation->notes }}</em>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endif
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 20px; color: #9ca3af;">No reconciliations found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer">
        <p><strong>FeedTan Community Microfinance Group - Bank Reconciliation Report</strong></p>
        <p>Report generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
        <p>Total Records: {{ $reconciliations->count() ?? 0 }}</p>
    </div>
</body>
</html>
