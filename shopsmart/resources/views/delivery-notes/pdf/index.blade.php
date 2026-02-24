<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Delivery Notes Report - {{ config('app.name', 'TmcsSmart') }}</title>
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
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        .status-in_transit {
            background: #dbeafe;
            color: #1e40af;
        }
        .status-delivered {
            background: #d1fae5;
            color: #065f46;
        }
        .status-cancelled {
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
        <div class="title">DELIVERY NOTES REPORT</div>
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
                        <td style="width: 25%; font-weight: bold;">Type</td>
                        <td>{{ ucfirst($filters['type']) }}</td>
                    </tr>
                    @endif
                    @if(!empty($filters['status']))
                    <tr>
                        <td style="width: 25%; font-weight: bold;">Status</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $filters['status'])) }}</td>
                    </tr>
                    @endif
                    @if(!empty($filters['date_from']) || !empty($filters['date_to']))
                    <tr>
                        <td style="width: 25%; font-weight: bold;">Date Range</td>
                        <td>{{ $filters['date_from'] ?? '-' }} to {{ $filters['date_to'] ?? '-' }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Delivery Notes Table -->
    <div class="section">
        <div class="section-header">Delivery Notes</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th style="width: 12%;">Delivery #</th>
                        <th style="width: 10%;">Type</th>
                        <th style="width: 18%;">Customer/Supplier</th>
                        <th style="width: 10%;">Delivery Date</th>
                        <th class="text-center" style="width: 8%;">Items</th>
                        <th style="width: 20%;">Delivery Address</th>
                        <th style="width: 10%;">Contact</th>
                        <th class="text-center" style="width: 12%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveryNotes ?? [] as $note)
                    <tr>
                        <td>{{ $note->delivery_number }}</td>
                        <td>{{ ucfirst($note->type) }}</td>
                        <td>
                            @if($note->customer)
                                {{ $note->customer->name }}
                            @elseif($note->supplier)
                                {{ $note->supplier->name }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($note->delivery_date)->format('M d, Y') }}</td>
                        <td class="text-center">{{ $note->items->count() ?? 0 }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($note->delivery_address ?? '-', 30) }}</td>
                        <td>{{ $note->contact_person ?? '-' }}</td>
                        <td class="text-center">
                            <span class="status-badge status-{{ str_replace('-', '_', $note->status) }}">
                                {{ ucfirst(str_replace('_', ' ', $note->status)) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 20px; color: #9ca3af;">No delivery notes found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer">
        <p><strong>FeedTan Community Microfinance Group - Delivery Notes Report</strong></p>
        <p>Report generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
        <p>Total Records: {{ $deliveryNotes->count() ?? 0 }}</p>
    </div>
</body>
</html>
