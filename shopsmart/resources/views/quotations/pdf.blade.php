<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quotation {{ $quotation->quotation_number }} - {{ $settings['company_name'] ?? 'TmcsSmart' }}</title>
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
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-approved {
            background: #d4edda;
            color: #155724;
        }
        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }
        .status-expired {
            background: #f8d7da;
            color: #721c24;
        }
        .status-converted {
            background: #d1ecf1;
            color: #0c5460;
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
            width: 60%;
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
            line-height: 1.6;
        }
        .expiry-warning {
            background: #fee2e2;
            border-left: 4px solid #dc2626;
            padding: 10px;
            margin: 15px 0;
            font-size: 9pt;
            color: #991b1b;
        }
        .expiry-info {
            background: #d1fae5;
            border-left: 4px solid #059669;
            padding: 10px;
            margin: 15px 0;
            font-size: 9pt;
            color: #065f46;
        }
        .conversion-info {
            background: #dbeafe;
            border-left: 4px solid #3b82f6;
            padding: 10px;
            margin: 15px 0;
            font-size: 9pt;
            color: #1e40af;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 7pt;
            color: #666;
            text-align: center;
        }
        .product-name {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 2px;
        }
        .product-details {
            font-size: 7pt;
            color: #6b7280;
            margin-top: 2px;
        }
        .product-sku {
            display: inline-block;
            background: #f3f4f6;
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 6.5pt;
            margin-right: 4px;
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
        <div class="title">QUOTATION</div>
        <div class="header-info">
            Quotation #: <strong>{{ $quotation->quotation_number }}</strong><br>
            Generated: {{ now()->format('Y-m-d H:i:s') }}<br>
            Status: 
            <span class="status-badge status-{{ strtolower($quotation->status) }}">
                {{ strtoupper($quotation->status) }}
            </span>
        </div>
    </div>

    <!-- Expiry Warning/Info -->
    @if($quotation->expiry_date)
        @php
            $daysRemaining = now()->diffInDays($quotation->expiry_date, false);
        @endphp
        @if($daysRemaining < 0)
            <div class="expiry-warning">
                <strong>⚠ EXPIRED:</strong> This quotation expired on {{ $quotation->expiry_date->format('F d, Y') }} ({{ abs($daysRemaining) }} {{ abs($daysRemaining) == 1 ? 'day' : 'days' }} ago)
            </div>
        @elseif($daysRemaining == 0)
            <div class="expiry-warning">
                <strong>⚠ EXPIRES TODAY:</strong> This quotation expires on {{ $quotation->expiry_date->format('F d, Y') }}
            </div>
        @elseif($daysRemaining <= 7)
            <div class="expiry-info">
                <strong>⏱ VALID UNTIL:</strong> {{ $quotation->expiry_date->format('F d, Y') }} ({{ $daysRemaining }} {{ $daysRemaining == 1 ? 'day' : 'days' }} remaining)
            </div>
        @endif
    @endif

    <!-- Company & Customer Information -->
    <div class="two-column">
        <div class="column">
            <div class="section">
                <div class="section-header">Bill To</div>
                <div class="section-content">
                    <table class="info-table">
                        <tr>
                            <td>Customer Name</td>
                            <td><strong>{{ $quotation->customer->name ?? 'Walk-in Customer' }}</strong></td>
                        </tr>
                        @if($quotation->customer)
                            @if($quotation->customer->email)
                            <tr>
                                <td>Email</td>
                                <td>{{ $quotation->customer->email }}</td>
                            </tr>
                            @endif
                            @if($quotation->customer->phone)
                            <tr>
                                <td>Phone</td>
                                <td>{{ $quotation->customer->phone }}</td>
                            </tr>
                            @endif
                            @if($quotation->customer->address)
                            <tr>
                                <td>Address</td>
                                <td>{{ $quotation->customer->address }}</td>
                            </tr>
                            @endif
                            @if($quotation->customer->tax_id)
                            <tr>
                                <td>Tax ID</td>
                                <td>{{ $quotation->customer->tax_id }}</td>
                            </tr>
                            @endif
                        @else
                            <tr>
                                <td>Note</td>
                                <td style="color: #9ca3af; font-style: italic;">No customer information</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="section">
                <div class="section-header">Quotation Details</div>
                <div class="section-content">
                    <table class="info-table">
                        <tr>
                            <td>Quotation Date</td>
                            <td><strong>{{ $quotation->quotation_date ? $quotation->quotation_date->format('F d, Y') : 'N/A' }}</strong></td>
                        </tr>
                        <tr>
                            <td>Time</td>
                            <td>{{ $quotation->created_at->format('h:i A') }}</td>
                        </tr>
                        @if($quotation->expiry_date)
                        <tr>
                            <td>Valid Until</td>
                            <td><strong>{{ $quotation->expiry_date->format('F d, Y') }}</strong></td>
                        </tr>
                        @endif
                        @if($quotation->warehouse)
                        <tr>
                            <td>Warehouse</td>
                            <td>{{ $quotation->warehouse->name }}</td>
                        </tr>
                        @endif
                        @if($quotation->user)
                        <tr>
                            <td>Prepared By</td>
                            <td>{{ $quotation->user->name }}</td>
                        </tr>
                        @endif
                        @if($quotation->is_sent && $quotation->sent_at)
                        <tr>
                            <td>Sent At</td>
                            <td>{{ $quotation->sent_at->format('M d, Y h:i A') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Company Information -->
    <div class="section">
        <div class="section-header">Company Information</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td>Company Name</td>
                    <td><strong>{{ $settings['company_name'] ?? 'TmcsSmart' }}</strong></td>
                </tr>
                @if($settings['company_address'] ?? '')
                <tr>
                    <td>Address</td>
                    <td>{{ $settings['company_address'] }}</td>
                </tr>
                @endif
                @if($settings['company_phone'] ?? '')
                <tr>
                    <td>Phone</td>
                    <td>{{ $settings['company_phone'] }}</td>
                </tr>
                @endif
                @if($settings['company_email'] ?? '')
                <tr>
                    <td>Email</td>
                    <td>{{ $settings['company_email'] }}</td>
                </tr>
                @endif
                @if($settings['company_website'] ?? '')
                <tr>
                    <td>Website</td>
                    <td>{{ $settings['company_website'] }}</td>
                </tr>
                @endif
                @if($settings['tax_id'] ?? '')
                <tr>
                    <td>Tax ID</td>
                    <td><strong>{{ $settings['tax_id'] }}</strong></td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    <!-- Items Table -->
    <div class="section">
        <div class="section-header">Items</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 35%;">Product / Description</th>
                        <th class="text-center" style="width: 10%;">Qty</th>
                        <th class="text-right" style="width: 15%;">Unit Price</th>
                        <th class="text-right" style="width: 15%;">Discount</th>
                        <th class="text-right" style="width: 20%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quotation->items as $index => $item)
                    <tr>
                        <td style="text-align: center; color: #9ca3af;">{{ $index + 1 }}</td>
                        <td>
                            <div class="product-name">{{ $item->product->name }}</div>
                            <div class="product-details">
                                @if($item->product->sku)
                                    <span class="product-sku">SKU: {{ $item->product->sku }}</span>
                                @endif
                                @if($item->product->category)
                                    <span>{{ $item->product->category->name }}</span>
                                @endif
                            </div>
                            @if($item->description)
                                <div style="margin-top: 3px; font-size: 7pt; color: #6b7280; font-style: italic;">
                                    {{ $item->description }}
                                </div>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            {{ number_format($item->quantity) }}
                            @if($item->product->unit)
                                <span style="color: #9ca3af; font-size: 7pt;">{{ $item->product->unit }}</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <strong>{{ number_format($item->unit_price, 0) }} TZS</strong>
                        </td>
                        <td style="text-align: right;">
                            @if($item->discount > 0)
                                <span style="color: #dc2626;">-{{ number_format($item->discount, 0) }} TZS</span>
                            @else
                                <span style="color: #9ca3af;">—</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <strong style="color: #009245;">{{ number_format($item->total, 0) }} TZS</strong>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Summary -->
    <div class="section">
        <div class="section-header">Summary</div>
        <div class="section-content">
            <table class="summary-table">
                <tr>
                    <td>Subtotal</td>
                    <td>{{ number_format($quotation->subtotal ?? 0, 0) }} TZS</td>
                </tr>
                @if(($quotation->discount ?? 0) > 0)
                <tr>
                    <td>Total Discount</td>
                    <td style="color: #dc2626;">-{{ number_format($quotation->discount, 0) }} TZS</td>
                </tr>
                @endif
                <tr>
                    <td>Tax</td>
                    <td>{{ number_format($quotation->tax ?? 0, 0) }} TZS</td>
                </tr>
                <tr class="total">
                    <td>Total Amount</td>
                    <td>{{ number_format($quotation->total ?? 0, 0) }} TZS</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Conversion Info -->
    @if($quotation->status === 'converted' && $quotation->converted_to_sale_id)
    <div class="conversion-info">
        <strong>✓ This quotation has been converted to a sale.</strong><br>
        <span style="font-size: 8pt;">
            Invoice Number: <strong>{{ $quotation->sale->invoice_number ?? 'N/A' }}</strong><br>
            Converted on: {{ $quotation->updated_at->format('F d, Y h:i A') }}
        </span>
    </div>
    @endif

    <!-- Terms & Conditions -->
    @if($quotation->terms_conditions)
    <div class="section">
        <div class="section-header">Terms & Conditions</div>
        <div class="section-content" style="white-space: pre-line;">{{ $quotation->terms_conditions }}</div>
    </div>
    @endif

    <!-- Notes -->
    @if($quotation->notes)
    <div class="section">
        <div class="section-header">Additional Notes</div>
        <div class="section-content" style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 10px; border-radius: 4px;">
            {{ $quotation->notes }}
        </div>
    </div>
    @endif

    <!-- Customer Notes -->
    @if($quotation->customer_notes)
    <div class="section">
        <div class="section-header">Customer Notes</div>
        <div class="section-content" style="white-space: pre-line;">{{ $quotation->customer_notes }}</div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p><strong>Thank you for your business!</strong></p>
        <p>This is a computer-generated quotation. No signature required.</p>
        <p style="margin-top: 8px;">
            Generated on {{ now()->format('F d, Y \a\t H:i:s') }} | 
            Quotation #{{ $quotation->quotation_number }}
        </p>
    </div>
</body>
</html>
