<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inventory Report - {{ $settings['company_name'] ?? 'ShopSmart' }}</title>
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
        .warning {
            color: #dc2626;
            font-weight: bold;
        }
        .success {
            color: #059669;
            font-weight: bold;
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
        <div class="title">INVENTORY REPORT</div>
        <div class="header-info">
            Generated: {{ now()->format('Y-m-d H:i:s') }}
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="section">
        <div class="section-header">Summary Statistics</div>
        <div class="section-content">
            <table class="summary-table">
                <tr>
                    <td>Total Products</td>
                    <td>{{ number_format($totalProducts, 0) }}</td>
                </tr>
                <tr>
                    <td>Active Products</td>
                    <td>{{ number_format($activeProducts, 0) }}</td>
                </tr>
                <tr>
                    <td>Total Stock Value (Cost)</td>
                    <td>{{ number_format($totalStockValue, 0) }} TSh</td>
                </tr>
                <tr>
                    <td>Total Stock Value (Selling)</td>
                    <td>{{ number_format($totalSellingValue, 0) }} TSh</td>
                </tr>
                <tr>
                    <td>Potential Profit</td>
                    <td class="success">{{ number_format($potentialProfit, 0) }} TSh</td>
                </tr>
                <tr>
                    <td>Low Stock Items</td>
                    <td class="warning">{{ number_format($lowStockCount, 0) }}</td>
                </tr>
                <tr>
                    <td>Out of Stock Items</td>
                    <td class="warning">{{ number_format($outOfStockCount, 0) }}</td>
                </tr>
                <tr class="total">
                    <td>Total Inventory Value</td>
                    <td>{{ number_format($totalStockValue, 0) }} TSh</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Inventory Details -->
    <div class="section">
        <div class="section-header">Product Inventory Details</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 25%;">Product Name</th>
                        <th style="width: 10%;">SKU</th>
                        <th style="width: 12%;">Category</th>
                        <th style="width: 10%;">Warehouse</th>
                        <th style="width: 8%;" class="text-center">Stock Qty</th>
                        <th style="width: 8%;" class="text-center">Low Alert</th>
                        <th style="width: 10%;" class="text-right">Cost Price</th>
                        <th style="width: 12%;" class="text-right">Stock Value</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $index => $product)
                    <tr>
                        <td style="text-align: center; color: #9ca3af;">{{ $index + 1 }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->sku ?? 'N/A' }}</td>
                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                        <td>{{ $product->warehouse->name ?? 'N/A' }}</td>
                        <td class="text-center {{ $product->stock_quantity <= 0 ? 'warning' : ($product->stock_quantity <= $product->low_stock_alert ? 'warning' : '') }}">
                            {{ number_format($product->stock_quantity, 0) }}
                        </td>
                        <td class="text-center">{{ number_format($product->low_stock_alert ?? 0, 0) }}</td>
                        <td class="text-right">{{ number_format($product->cost_price ?? 0, 0) }} TSh</td>
                        <td class="text-right"><strong style="color: #009245;">{{ number_format(($product->stock_quantity ?? 0) * ($product->cost_price ?? 0), 0) }} TSh</strong></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background: #009245; color: white; font-weight: bold;">
                        <td colspan="8" class="text-right">TOTAL STOCK VALUE:</td>
                        <td class="text-right">{{ number_format($totalStockValue, 0) }} TSh</td>
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
            Inventory Report
        </p>
    </div>
</body>
</html>
