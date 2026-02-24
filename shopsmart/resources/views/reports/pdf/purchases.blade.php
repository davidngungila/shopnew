<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Report - {{ $settings['company_name'] ?? 'ShopSmart' }}</title>
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
            <img src="{{ $headerBase64 }}" alt="Company Header" class="header-image">
            @endif
        </div>
        <div class="title">PURCHASE REPORT</div>
        <div class="header-info">
            Period: <strong>{{ \Carbon\Carbon::parse($dateFrom)->format('F d, Y') }} to {{ \Carbon\Carbon::parse($dateTo)->format('F d, Y') }}</strong><br>
            Generated: {{ now()->format('Y-m-d H:i:s') }}
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="section">
        <div class="section-header">Summary Statistics</div>
        <div class="section-content">
            <table class="summary-table">
                <tr>
                    <td>Total Purchases</td>
                    <td>{{ number_format($totalPurchases, 0) }} TSh</td>
                </tr>
                <tr>
                    <td>Total Orders</td>
                    <td>{{ number_format($totalOrders, 0) }}</td>
                </tr>
                <tr>
                    <td>Average Order Value</td>
                    <td>{{ number_format($averageOrder, 0) }} TSh</td>
                </tr>
                <tr class="total">
                    <td>Total Cost</td>
                    <td>{{ number_format($totalPurchases, 0) }} TSh</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Purchases by Supplier -->
    @if($purchasesBySupplier->count() > 0)
    <div class="section">
        <div class="section-header">Purchases by Supplier</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th>Supplier</th>
                        <th class="text-right">Total Amount</th>
                        <th class="text-center">Orders</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchasesBySupplier as $data)
                    <tr>
                        <td>{{ $data['supplier']->name ?? 'N/A' }}</td>
                        <td class="text-right"><strong style="color: #009245;">{{ number_format($data['total'], 0) }} TSh</strong></td>
                        <td class="text-center">{{ $data['count'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Purchase Details -->
    <div class="section">
        <div class="section-header">Purchase Details</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 12%;">Date</th>
                        <th style="width: 12%;">Invoice #</th>
                        <th style="width: 20%;">Supplier</th>
                        <th style="width: 10%;" class="text-center">Status</th>
                        <th style="width: 10%;" class="text-right">Items</th>
                        <th style="width: 12%;" class="text-right">Subtotal</th>
                        <th style="width: 9%;" class="text-right">Tax</th>
                        <th style="width: 10%;" class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchases as $index => $purchase)
                    <tr>
                        <td style="text-align: center; color: #9ca3af;">{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('M d, Y') }}</td>
                        <td>{{ $purchase->invoice_number ?? 'N/A' }}</td>
                        <td>{{ $purchase->supplier->name ?? 'N/A' }}</td>
                        <td class="text-center">{{ ucfirst($purchase->status) }}</td>
                        <td class="text-right">{{ $purchase->items->count() }}</td>
                        <td class="text-right">{{ number_format($purchase->subtotal ?? 0, 0) }} TSh</td>
                        <td class="text-right">{{ number_format($purchase->tax ?? 0, 0) }} TSh</td>
                        <td class="text-right"><strong style="color: #009245;">{{ number_format($purchase->total, 0) }} TSh</strong></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background: #009245; color: white; font-weight: bold;">
                        <td colspan="6" class="text-right">TOTAL:</td>
                        <td class="text-right">{{ number_format($purchases->sum('subtotal'), 0) }} TSh</td>
                        <td class="text-right">{{ number_format($purchases->sum('tax'), 0) }} TSh</td>
                        <td class="text-right">{{ number_format($totalPurchases, 0) }} TSh</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Company Information -->
    <div class="section">
        <div class="section-header">Company Information</div>
        <div class="section-content">
            <table class="info-table">
                <tr>
                    <td>Company Name</td>
                    <td><strong>{{ $settings['company_name'] ?? 'ShopSmart' }}</strong></td>
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

    <div class="footer">
        <p><strong>Thank you for your business!</strong></p>
        <p>This is a computer-generated report. No signature required.</p>
        <p style="margin-top: 8px;">
            Generated on {{ now()->format('F d, Y \a\t H:i:s') }} | 
            Purchase Report ({{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }})
        </p>
    </div>
</body>
</html>
