<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Expense Categories Report - {{ config('app.name', 'TmcsSmart') }}</title>
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
        <div class="title">EXPENSE CATEGORIES</div>
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
                    @if(isset($filters['is_active']) && $filters['is_active'] !== '')
                    <tr>
                        <td style="width: 30%; font-weight: bold;">Status</td>
                        <td>{{ $filters['is_active'] == '1' ? 'Active' : 'Inactive' }}</td>
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
            <div class="stats-cell stats-label">Total Categories:</div>
            <div class="stats-cell">{{ number_format($totalCategories ?? 0, 0) }}</div>
            <div class="stats-cell stats-label">Active Categories:</div>
            <div class="stats-cell" style="color: #059669; font-weight: 600;">{{ number_format($activeCategories ?? 0, 0) }}</div>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="section">
        <div class="section-header">Expense Categories</div>
        <div class="section-content">
            <table>
                <thead>
                    <tr>
                        <th style="width: 12%;">Code</th>
                        <th style="width: 30%;">Category Name</th>
                        <th style="width: 25%;">Linked Account</th>
                        <th class="text-center" style="width: 15%;">Status</th>
                        <th style="width: 18%;">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories ?? [] as $category)
                    <tr class="{{ !$category->is_active ? 'inactive' : '' }}" style="{{ !$category->is_active ? 'color: #9ca3af;' : '' }}">
                        <td>{{ $category->code ?? '-' }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->account->account_name ?? '-' }}</td>
                        <td class="text-center">
                            @if($category->is_active)
                                <span style="color: #059669;">Active</span>
                            @else
                                <span style="color: #9ca3af;">Inactive</span>
                            @endif
                        </td>
                        <td>{{ \Illuminate\Support\Str::limit($category->description ?? '-', 40) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 20px; color: #9ca3af;">No categories found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer">
        <p><strong>FeedTan Community Microfinance Group - Expense Categories Report</strong></p>
        <p>Report generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
        <p>Total Records: {{ $categories->count() ?? 0 }}</p>
    </div>
</body>
</html>
