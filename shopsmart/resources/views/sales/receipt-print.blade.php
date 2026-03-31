<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $sale->invoice_number ?? $sale->id }}</title>
    @php
        $companyName = \App\Models\Setting::get('company_name', 'ShopSmart');
        $companyAddress = \App\Models\Setting::get('company_address', 'Dar es Salaam, Tanzania');
        $companyPhone = \App\Models\Setting::get('company_phone', '+255 XXX XXX XXX');
        $companyEmail = \App\Models\Setting::get('company_email', '');
        $companyLogo = \App\Models\Setting::get('company_logo', '');
        $companyTaxNumber = \App\Models\Setting::get('company_tax_number', '');
    @endphp
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        @media print {
            @page {
                size: A4;
                margin: 15mm;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .action-buttons {
                display: none !important;
            }
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #2c3e50;
            background: #f8f9fa;
            padding: 20px;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }
        
        .invoice-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 40%;
            height: 200%;
            background: rgba(255,255,255,0.1);
            transform: rotate(35deg);
        }
        
        .header-content {
            position: relative;
            z-index: 1;
        }
        
        .company-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
        }
        
        .company-details h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .company-details p {
            font-size: 14px;
            opacity: 0.9;
            margin: 4px 0;
        }
        
        .company-logo {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .invoice-title {
            text-align: center;
            padding: 30px;
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }
        
        .invoice-title h2 {
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        
        .invoice-number {
            font-size: 18px;
            color: #6c757d;
            font-weight: 500;
        }
        
        .invoice-body {
            padding: 40px;
        }
        
        .invoice-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .meta-section h3 {
            font-size: 14px;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .meta-section p {
            margin: 8px 0;
            font-size: 15px;
        }
        
        .meta-section strong {
            color: #2c3e50;
            font-weight: 600;
            display: inline-block;
            min-width: 100px;
        }
        
        .items-section {
            margin-bottom: 40px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .items-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .items-table th {
            padding: 16px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .items-table tbody tr {
            border-bottom: 1px solid #e9ecef;
            transition: background-color 0.2s;
        }
        
        .items-table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .items-table td {
            padding: 16px;
            font-size: 15px;
            vertical-align: top;
        }
        
        .item-name {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .item-sku {
            font-size: 12px;
            color: #6c757d;
            margin-top: 4px;
        }
        
        .item-quantity {
            text-align: center;
            font-weight: 600;
        }
        
        .item-price {
            text-align: right;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .summary-section {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 40px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            font-size: 16px;
        }
        
        .summary-row.total {
            border-top: 2px solid #dee2e6;
            margin-top: 12px;
            padding-top: 20px;
            font-size: 20px;
            font-weight: 700;
            color: #2c3e50;
        }
        
        .summary-label {
            color: #6c757d;
        }
        
        .summary-value {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .payment-section {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 40px;
        }
        
        .payment-section h3 {
            font-size: 18px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .payment-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }
        
        .payment-detail {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .invoice-footer {
            background: #2c3e50;
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .footer-content {
            margin-bottom: 20px;
        }
        
        .footer-content h3 {
            font-size: 18px;
            margin-bottom: 12px;
        }
        
        .footer-content p {
            opacity: 0.8;
            margin: 8px 0;
        }
        
        .footer-meta {
            border-top: 1px solid rgba(255,255,255,0.2);
            padding-top: 20px;
            font-size: 12px;
            opacity: 0.7;
        }
        
        .action-buttons {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            display: flex;
            gap: 10px;
        }
        
        .action-btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            text-decoration: none;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }
        
        .print-btn {
            background: #007bff;
            color: white;
        }
        
        .pdf-btn {
            background: #dc3545;
            color: white;
        }
        
        .close-btn {
            background: #6c757d;
            color: white;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-left: 12px;
        }
        
        .status-completed {
            background: #d4edda;
            color: #155724;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        
        @media (max-width: 768px) {
            .invoice-meta {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .company-info {
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 20px;
            }
            
            .action-buttons {
                position: static;
                justify-content: center;
                margin-bottom: 20px;
            }
            
            .items-table {
                font-size: 12px;
            }
            
            .items-table th,
            .items-table td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="action-buttons no-print">
        <button onclick="window.print()" class="action-btn print-btn">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2H5zM6 5v1h4V5H6zm6 1V5h1a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h1v1h8zm-1 3H5v4h6V9z"/>
            </svg>
            Print
        </button>
        <a href="{{ route('sales.pdf', $sale) }}" target="_blank" class="action-btn pdf-btn">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
            </svg>
            Download PDF
        </a>
        <button onclick="window.close()" class="action-btn close-btn">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.708 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
            </svg>
            Close
        </button>
    </div>

    <div class="invoice-container">
        <div class="invoice-header">
            <div class="header-content">
                <div class="company-info">
                    <div class="company-details">
                        <h1>{{ $companyName }}</h1>
                        <p>📍 {{ $companyAddress }}</p>
                        <p>📞 {{ $companyPhone }}</p>
                        @if($companyEmail)
                        <p>✉️ {{ $companyEmail }}</p>
                        @endif
                        @if($companyTaxNumber)
                        <p>🏢 TIN: {{ $companyTaxNumber }}</p>
                        @endif
                    </div>
                    <div class="company-logo">
                        @if($companyLogo && file_exists(public_path('storage/' . $companyLogo)))
                            <img src="{{ asset('storage/' . $companyLogo) }}" alt="{{ $companyName }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
                        @else
                            {{ strtoupper(substr($companyName, 0, 2)) }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="invoice-title">
            <h2>TAX INVOICE</h2>
            <div class="invoice-number">
                Invoice #: {{ $sale->invoice_number ?? str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}
                <span class="status-badge status-{{ $sale->status ?? 'pending' }}">
                    {{ $sale->status ?? 'pending' }}
                </span>
            </div>
        </div>

        <div class="invoice-body">
            <div class="invoice-meta">
                <div class="meta-section">
                    <h3>Invoice Details</h3>
                    <p><strong>Date:</strong> {{ $sale->created_at->setTimezone('Africa/Dar_es_Salaam')->format('d M Y') }}</p>
                    <p><strong>Time:</strong> {{ $sale->created_at->setTimezone('Africa/Dar_es_Salaam')->format('h:i A') }}</p>
                    <p><strong>Cashier:</strong> {{ $sale->user->name ?? 'System' }}</p>
                    <p><strong>Warehouse:</strong> {{ $sale->warehouse->name ?? 'Main Store' }}</p>
                </div>
                
                <div class="meta-section">
                    <h3>Customer Information</h3>
                    @if($sale->customer)
                        <p><strong>Name:</strong> {{ $sale->customer->name }}</p>
                        @if($sale->customer->phone)
                        <p><strong>Phone:</strong> {{ $sale->customer->phone }}</p>
                        @endif
                        @if($sale->customer->email)
                        <p><strong>Email:</strong> {{ $sale->customer->email }}</p>
                        @endif
                        @if($sale->customer->address)
                        <p><strong>Address:</strong> {{ $sale->customer->address }}</p>
                        @endif
                    @else
                        <p><strong>Name:</strong> Walk-in Customer</p>
                        <p><strong>Type:</strong> Cash Sale</p>
                    @endif
                </div>
            </div>

            <div class="items-section">
                <table class="items-table">
                    <thead>
                        <tr>
                            <th style="width: 50%;">Item Description</th>
                            <th style="width: 15%;">Quantity</th>
                            <th style="width: 15%;">Unit Price</th>
                            <th style="width: 20%;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $item)
                        <tr>
                            <td>
                                <div class="item-name">{{ $item->product->name ?? 'Unknown Item' }}</div>
                                @if($item->product && $item->product->sku)
                                <div class="item-sku">SKU: {{ $item->product->sku }}</div>
                                @endif
                            </td>
                            <td class="item-quantity">{{ number_format($item->quantity) }}</td>
                            <td class="item-price">TZS {{ number_format($item->unit_price, 0) }}</td>
                            <td class="item-price">TZS {{ number_format($item->total, 0) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="summary-section">
                <div class="summary-row">
                    <span class="summary-label">Subtotal:</span>
                    <span class="summary-value">TZS {{ number_format($sale->subtotal, 0) }}</span>
                </div>
                @if($sale->discount > 0)
                <div class="summary-row">
                    <span class="summary-label">Discount:</span>
                    <span class="summary-value" style="color: #dc3545;">- TZS {{ number_format($sale->discount, 0) }}</span>
                </div>
                @endif
                @if($sale->tax > 0)
                <div class="summary-row">
                    <span class="summary-label">Tax (VAT):</span>
                    <span class="summary-value">TZS {{ number_format($sale->tax, 0) }}</span>
                </div>
                @endif
                <div class="summary-row total">
                    <span>TOTAL AMOUNT:</span>
                    <span>TZS {{ number_format($sale->total, 0) }}</span>
                </div>
            </div>

            <div class="payment-section">
                <h3>
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V3zm1 0v10h12V3H2z"/>
                        <path d="M2 5h12v1H2V5zm0 3h12v1H2V8zm0 3h6v1H2v-1z"/>
                    </svg>
                    Payment Information
                </h3>
                <div class="payment-details">
                    <div class="payment-detail">
                        <span>Method:</span>
                        <span>{{ strtoupper(str_replace('_', ' ', $sale->payment_method ?? 'cash')) }}</span>
                    </div>
                    <div class="payment-detail">
                        <span>Amount Paid:</span>
                        <span>TZS {{ number_format($sale->total, 0) }}</span>
                    </div>
                    @if($sale->payment_method === 'card' && $sale->card_last_four)
                    <div class="payment-detail">
                        <span>Card:</span>
                        <span>**** **** **** {{ $sale->card_last_four }}</span>
                    </div>
                    @endif
                    @if($sale->payment_method === 'credit')
                    @php
                        $sale->load('payments');
                        $totalPaid = $sale->payments->sum('amount');
                        $balance = $sale->total - $totalPaid;
                    @endphp
                    <div class="payment-detail">
                        <span>Amount Paid:</span>
                        <span>TZS {{ number_format($totalPaid, 0) }}</span>
                    </div>
                    <div class="payment-detail">
                        <span>Balance:</span>
                        <span>TZS {{ number_format($balance, 0) }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="invoice-footer">
            <div class="footer-content">
                <h3>Thank You For Your Business!</h3>
                <p>This is a computer-generated invoice and does not require a signature.</p>
                <p>For any inquiries, please contact us at {{ $companyPhone }}</p>
            </div>
            <div class="footer-meta">
                <p>Generated on {{ now()->setTimezone('Africa/Dar_es_Salaam')->format('d M Y h:i A') }}</p>
                <p>Invoice #{{ $sale->invoice_number ?? str_pad($sale->id, 6, '0', STR_PAD_LEFT) }} | Page 1 of 1</p>
            </div>
        </div>
    </div>

    <script>
        // Auto-print when page loads (optional - uncomment if needed)
        // window.onload = function() {
        //     setTimeout(() => {
        //         window.print();
        //     }, 1000);
        // };
        
        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey || e.metaKey) {
                switch(e.key) {
                    case 'p':
                        e.preventDefault();
                        window.print();
                        break;
                    case 's':
                        e.preventDefault();
                        window.open('{{ route('sales.pdf', $sale) }}', '_blank');
                        break;
                }
            }
        });
    </script>
</body>
</html>

