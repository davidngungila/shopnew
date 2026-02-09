<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Delivery Note {{ $deliveryNote->delivery_number }} - {{ config('app.name', 'TmcsSmart') }}</title>
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
            width: 35%;
            color: #374151;
            background: #f9fafb;
            border-right: 1px solid #e5e7eb;
        }
        .info-table td:last-child {
            color: #1a1a1a;
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
        <div class="title">DELIVERY NOTE</div>
        <div class="header-info">
            Delivery #: <strong>{{ $deliveryNote->delivery_number }}</strong><br>
            Generated: {{ now()->format('Y-m-d H:i:s') }}<br>
            Status: 
            <span class="status-badge status-{{ str_replace('-', '_', $deliveryNote->status) }}">
                {{ strtoupper(str_replace('_', ' ', $deliveryNote->status)) }}
            </span>
        </div>
    </div>

    <!-- Delivery & Contact Information -->
    <div class="two-column">
        <div class="column">
            <div class="section">
                <div class="section-header">Delivery Information</div>
                <div class="section-content">
                    <table class="info-table">
                        <tr>
                            <td>Delivery Date</td>
                            <td><strong>{{ \Carbon\Carbon::parse($deliveryNote->delivery_date)->format('F d, Y') }}</strong></td>
                        </tr>
                        <tr>
                            <td>Type</td>
                            <td>{{ ucfirst($deliveryNote->type) }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>
                                <span class="status-badge status-{{ str_replace('-', '_', $deliveryNote->status) }}">
                                    {{ ucfirst(str_replace('_', ' ', $deliveryNote->status)) }}
                                </span>
                            </td>
                        </tr>
                        @if($deliveryNote->delivery_address)
                        <tr>
                            <td>Delivery Address</td>
                            <td>{{ $deliveryNote->delivery_address }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="section">
                <div class="section-header">{{ $deliveryNote->type === 'sale' ? 'Customer' : ($deliveryNote->type === 'purchase' ? 'Supplier' : 'Contact') }} Information</div>
                <div class="section-content">
                    <table class="info-table">
                        @if($deliveryNote->customer)
                        <tr>
                            <td>Customer</td>
                            <td><strong>{{ $deliveryNote->customer->name }}</strong></td>
                        </tr>
                        @if($deliveryNote->customer->phone)
                        <tr>
                            <td>Phone</td>
                            <td>{{ $deliveryNote->customer->phone }}</td>
                        </tr>
                        @endif
                        @endif
                        @if($deliveryNote->supplier)
                        <tr>
                            <td>Supplier</td>
                            <td><strong>{{ $deliveryNote->supplier->name }}</strong></td>
                        </tr>
                        @endif
                        @if($deliveryNote->contact_person)
                        <tr>
                            <td>Contact Person</td>
                            <td>{{ $deliveryNote->contact_person }}</td>
                        </tr>
                        @endif
                        @if($deliveryNote->contact_phone)
                        <tr>
                            <td>Contact Phone</td>
                            <td>{{ $deliveryNote->contact_phone }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="section">
        <div class="section-header">Delivery Items</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 40%;">Item Name</th>
                        <th class="text-right" style="width: 15%;">Quantity</th>
                        <th style="width: 15%;">Unit</th>
                        <th style="width: 25%;">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveryNote->items ?? [] as $index => $item)
                    <tr>
                        <td style="text-align: center; color: #9ca3af;">{{ $index + 1 }}</td>
                        <td><strong>{{ $item->item_name }}</strong></td>
                        <td class="text-right" style="font-weight: 600;">{{ number_format($item->quantity ?? 0, 0) }}</td>
                        <td>{{ $item->unit ?? 'pcs' }}</td>
                        <td>{{ $item->description ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 20px; color: #9ca3af;">No items found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($deliveryNote->notes)
    <div class="section">
        <div class="section-header">Notes</div>
        <div class="section-content" style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 10px; border-radius: 4px;">
            {{ $deliveryNote->notes }}
        </div>
    </div>
    @endif

    <div class="footer">
        <p><strong>FeedTan Community Microfinance Group - Delivery Note</strong></p>
        <p>Report generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
        <p>Delivery Note #{{ $deliveryNote->delivery_number }}</p>
    </div>
</body>
</html>
