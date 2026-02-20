<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reportTitle ?? 'Business Report' }} - {{ $companyName ?? 'TmcsSmart' }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Arial, sans-serif;
            line-height: 1.6;
            color: #111827;
            max-width: 720px;
            margin: 0 auto;
            padding: 24px 16px;
            background-color: #f3f4f6;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 28px 24px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.12);
        }
        .header {
            text-align: center;
            margin-bottom: 24px;
            padding-bottom: 18px;
            border-bottom: 2px solid #009245;
        }
        .brand-name {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #009245;
            margin: 0 0 4px 0;
        }
        .brand-tagline {
            margin: 0;
            font-size: 12px;
            color: #6b7280;
        }
        .report-title {
            font-size: 20px;
            font-weight: 700;
            margin: 18px 0 4px 0;
            color: #111827;
        }
        .report-period {
            font-size: 13px;
            color: #6b7280;
            margin: 0;
        }
        .intro {
            font-size: 14px;
            color: #374151;
            margin: 20px 0;
        }
        .summary-cards {
            display: table;
            width: 100%;
            border-spacing: 0 8px;
            margin-bottom: 20px;
        }
        .summary-card {
            display: table-row;
        }
        .summary-label,
        .summary-value {
            display: table-cell;
            padding: 8px 10px;
            font-size: 13px;
            border-radius: 6px;
        }
        .summary-label {
            background-color: #f9fafb;
            color: #4b5563;
            width: 50%;
        }
        .summary-value {
            text-align: right;
            font-weight: 600;
            color: #111827;
        }
        .summary-value.positive { color: #059669; }
        .summary-value.negative { color: #b91c1c; }
        .summary-value.neutral { color: #2563eb; }

        .section-title {
            font-size: 15px;
            font-weight: 600;
            margin: 22px 0 8px 0;
            color: #111827;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 4px 0 18px 0;
            font-size: 12px;
        }
        th {
            background-color: #009245;
            color: #ffffff;
            padding: 8px 6px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #009245;
        }
        td {
            padding: 7px 6px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
        }
        tr:nth-child(even) td {
            background-color: #f9fafb;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            background-color: #e5f6ee;
            color: #047857;
        }
        .footer {
            margin-top: 22px;
            padding-top: 14px;
            border-top: 1px solid #e5e7eb;
            font-size: 11px;
            color: #6b7280;
            text-align: center;
        }
        .cta-button {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 999px;
            background-color: #009245;
            color: #ffffff;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            margin: 10px 0 4px 0;
        }
        .cta-button:hover {
            background-color: #007a38;
        }
        .muted {
            color: #6b7280;
            font-size: 12px;
        }
        .kpi-grid {
            display: table;
            width: 100%;
            border-spacing: 0 6px;
        }
        .kpi-row {
            display: table-row;
        }
        .kpi-label,
        .kpi-value {
            display: table-cell;
            padding: 6px 8px;
            font-size: 12px;
        }
        .kpi-label {
            color: #4b5563;
        }
        .kpi-value {
            text-align: right;
            font-weight: 600;
            color: #111827;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="brand-name">{{ $companyName ?? 'TmcsSmart' }}</div>
            <p class="brand-tagline">
                {{ $companyTagline ?? 'Smart retail & financial insights in one place' }}
            </p>

            <h1 class="report-title">
                {{ $reportTitle ?? 'Business Performance Report' }}
            </h1>
            @if(!empty($reportPeriod))
                <p class="report-period">{{ $reportPeriod }}</p>
            @endif
        </div>

        <p class="intro">
            {{ $introText ?? 'Here is a quick summary of your latest report generated from TmcsSmart. Use these insights to make faster, data‑driven decisions for your business.' }}
        </p>

        @php
            $summaryMetrics = $summaryMetrics ?? [];
        @endphp

        @if(count($summaryMetrics))
            <div class="summary-cards">
                @foreach($summaryMetrics as $metric)
                    <div class="summary-card">
                        <div class="summary-label">
                            {{ $metric['label'] ?? '' }}
                            @if(!empty($metric['badge']))
                                <span class="badge" style="margin-left:6px;">{{ $metric['badge'] }}</span>
                            @endif
                        </div>
                        @php
                            $class = 'summary-value';
                            if (($metric['trend'] ?? '') === 'up')  $class .= ' positive';
                            if (($metric['trend'] ?? '') === 'down') $class .= ' negative';
                            if (($metric['trend'] ?? '') === 'neutral') $class .= ' neutral';
                        @endphp
                        <div class="{{ $class }}">
                            {{ $metric['value'] ?? '' }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @php
            $topRows = $topRows ?? [];
        @endphp

        @if(count($topRows))
            <h2 class="section-title">
                {{ $topRowsTitle ?? 'Key Lines from the Report' }}
            </h2>
            <table role="presentation">
                <thead>
                    <tr>
                        @foreach(($topRowsHeaders ?? []) as $header)
                            <th>{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($topRows as $row)
                        <tr>
                            @foreach($row as $cell)
                                <td>{!! $cell !!}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @php
            $extraNotes = $extraNotes ?? null;
        @endphp

        @if($extraNotes)
            <h2 class="section-title">Notes & Observations</h2>
            <p class="muted" style="text-align: left;">
                {!! nl2br(e($extraNotes)) !!}
            </p>
        @endif

        @if(!empty($ctaUrl))
            <div style="text-align:center; margin-top: 12px;">
                <a href="{{ $ctaUrl }}" class="cta-button">
                    {{ $ctaLabel ?? 'Open full report in TmcsSmart' }}
                </a>
                @if(!empty($ctaHint))
                    <p class="muted" style="margin-top: 4px;">{{ $ctaHint }}</p>
                @endif
            </div>
        @endif

        <div class="footer">
            <p>{{ $footerText ?? 'This report was generated automatically by TmcsSmart based on your latest data.' }}</p>
            <p style="margin-top:8px;">© {{ date('Y') }} {{ $companyName ?? 'TmcsSmart' }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>





