<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liabilities Report - {{ config('app.name', 'TmcsSmart') }}</title>
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
        .status-active {
            background: #dbeafe;
            color: #1e40af;
        }
        .status-paid {
            background: #d1fae5;
            color: #065f46;
        }
        .status-overdue {
            background: #fee2e2;
            color: #991b1b;
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
        <div class="title">LIABILITIES REPORT</div>
        <div class="header-info">
            Generated: {{ now()->format('Y-m-d H:i:s') }}<br>
            Company: {{ config('app.name', 'TmcsSmart') }}
        </div>
    </div>

    @if(isset($filters) && collect($filters)->filter(fn($v) => $v !== null && $v !== '')->count() > 0)
    <div class="section" style="margin-top: 0;">
        <div class="section-header">Applied Filters</div>
        <div class="section-content">
            <table>
                <tbody>
                    @if(!empty($filters['type']))
                    <tr>
                        <td style="width: 30%; font-weight: bold;">Type</td>
                        <td>{{ ucfirst($filters['type']) }}</td>
                    </tr>
                    @endif
                    @if(!empty($filters['status']))
                    <tr>
                        <td style="width: 30%; font-weight: bold;">Status</td>
                        <td>{{ ucfirst($filters['status']) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Statistics -->
    <div class="stats">
        <div class="stats-row">
            <div class="stats-cell stats-label">Total Liabilities:</div>
            <div class="stats-cell" style="color: #dc2626; font-weight: 600;">TSh {{ number_format($totalLiabilities ?? 0, 0) }}</div>
            <div class="stats-cell stats-label">Active Liabilities:</div>
            <div class="stats-cell" style="color: #dc2626; font-weight: 600;">TSh {{ number_format($activeLiabilities ?? 0, 0) }}</div>
            <div class="stats-cell stats-label">Overdue Liabilities:</div>
            <div class="stats-cell" style="color: #dc2626; font-weight: 600;">TSh {{ number_format($overdueLiabilities ?? 0, 0) }}</div>
        </div>
    </div>

    <!-- Liabilities Table -->
    <div class="section">
        <div class="section-header">Liabilities</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th style="width: 12%;">Liability #</th>
                        <th style="width: 20%;">Name</th>
                        <th style="width: 12%;">Type</th>
                        <th style="width: 18%;">Start / Due Date</th>
                        <th class="text-right" style="width: 12%;">Principal (TSh)</th>
                        <th class="text-right" style="width: 12%;">Outstanding (TSh)</th>
                        <th class="text-center" style="width: 14%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($liabilities ?? [] as $liability)
                    <tr>
                        <td>{{ $liability->liability_number }}</td>
                        <td>{{ $liability->name }}</td>
                        <td>{{ ucfirst($liability->type) }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($liability->start_date)->format('M d, Y') }}
                            @if($liability->due_date)
                            <br><span style="font-size: 7pt; color: #6b7280;">Due: {{ \Carbon\Carbon::parse($liability->due_date)->format('M d, Y') }}</span>
                            @endif
                        </td>
                        <td class="text-right">{{ number_format($liability->principal_amount ?? 0, 0) }}</td>
                        <td class="text-right" style="font-weight: 600; color: #dc2626;">{{ number_format($liability->outstanding_balance ?? 0, 0) }}</td>
                        <td class="text-center">
                            <span class="status-badge status-{{ $liability->status }}">
                                {{ ucfirst($liability->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 20px; color: #9ca3af;">No liabilities found</td>
                    </tr>
                    @endforelse
                    <tr style="background: #f3f4f6; font-weight: 700; border-top: 2px solid #009245;">
                        <td colspan="5"><strong>TOTAL OUTSTANDING</strong></td>
                        <td class="text-right" style="font-weight: 700; color: #dc2626;"><strong>TSh {{ number_format($totalLiabilities ?? 0, 0) }}</strong></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer">
        <p><strong>FeedTan Community Microfinance Group - Liabilities Report</strong></p>
        <p>Report generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
        <p>Total Records: {{ $liabilities->count() ?? 0 }}</p>
    </div>
</body>
</html>
