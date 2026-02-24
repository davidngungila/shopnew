<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Expenses Report - {{ config('app.name', 'TmcsSmart') }}</title>
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
        <div class="title">EXPENSES REPORT</div>
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
                    @if(!empty($filters['search']))
                    <tr>
                        <td style="width: 30%; font-weight: bold;">Search</td>
                        <td>{{ $filters['search'] }}</td>
                    </tr>
                    @endif
                    @if(!empty($filters['category']))
                    <tr>
                        <td style="width: 30%; font-weight: bold;">Category</td>
                        <td>{{ $filters['category'] }}</td>
                    </tr>
                    @endif
                    @if(!empty($filters['payment_method']))
                    <tr>
                        <td style="width: 30%; font-weight: bold;">Payment Method</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $filters['payment_method'])) }}</td>
                    </tr>
                    @endif
                    @if(!empty($filters['date_from']) || !empty($filters['date_to']))
                    <tr>
                        <td style="width: 30%; font-weight: bold;">Date Range</td>
                        <td>{{ $filters['date_from'] ?? '-' }} to {{ $filters['date_to'] ?? '-' }}</td>
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
            <div class="stats-cell stats-label">Total Expenses:</div>
            <div class="stats-cell"><strong>TSh {{ number_format($totalAmount ?? 0, 0) }}</strong></div>
            <div class="stats-cell stats-label">Number of Expenses:</div>
            <div class="stats-cell">{{ number_format($expenses->count() ?? 0, 0) }}</div>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="section">
        <div class="section-header">Expenses</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th style="width: 15%;">Expense #</th>
                        <th style="width: 12%;">Date</th>
                        <th style="width: 18%;">Category</th>
                        <th style="width: 25%;">Description</th>
                        <th style="width: 15%;">Payment Method</th>
                        <th class="text-right" style="width: 15%;">Amount (TSh)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses ?? [] as $expense)
                    <tr>
                        <td>{{ $expense->expense_number }}</td>
                        <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('M d, Y') }}</td>
                        <td>{{ $expense->category }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($expense->description ?? '-', 40) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $expense->payment_method)) }}</td>
                        <td class="text-right" style="font-weight: 600; color: #dc2626;">{{ number_format($expense->amount ?? 0, 0) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px; color: #9ca3af;">No expenses found</td>
                    </tr>
                    @endforelse
                    <tr style="background: #f3f4f6; font-weight: 700; border-top: 2px solid #009245;">
                        <td colspan="5"><strong>TOTAL</strong></td>
                        <td class="text-right" style="font-weight: 700; color: #dc2626;"><strong>TSh {{ number_format($totalAmount ?? 0, 0) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    @if(isset($categoryBreakdown) && $categoryBreakdown->count() > 0)
    <div class="section">
        <div class="section-header">Category Breakdown</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th style="width: 60%;">Category</th>
                        <th class="text-right" style="width: 20%;">Count</th>
                        <th class="text-right" style="width: 20%;">Total Amount (TSh)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categoryBreakdown as $breakdown)
                    <tr>
                        <td>{{ $breakdown->category }}</td>
                        <td class="text-right">{{ number_format($breakdown->count ?? 0, 0) }}</td>
                        <td class="text-right" style="font-weight: 600; color: #dc2626;">{{ number_format($breakdown->total ?? 0, 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="footer">
        <p><strong>FeedTan Community Microfinance Group - Expenses Report</strong></p>
        <p>Report generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
        <p>Total Records: {{ $expenses->count() ?? 0 }}</p>
    </div>
</body>
</html>
