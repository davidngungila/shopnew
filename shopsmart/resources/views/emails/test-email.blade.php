<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Configuration Test - {{ $companyName ?? 'TmcsSmart' }}</title>
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
            border-radius: 12px;
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
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 999px;
            background-color: #e5f6ee;
            color: #047857;
            font-size: 11px;
            font-weight: 600;
            margin-top: 8px;
        }
        .title {
            font-size: 18px;
            font-weight: 700;
            margin: 16px 0 6px 0;
        }
        .subtitle {
            font-size: 13px;
            color: #6b7280;
            margin: 0;
        }
        .section {
            margin-top: 20px;
        }
        .section-title {
            font-size: 14px;
            font-weight: 600;
            margin: 0 0 6px 0;
            color: #111827;
        }
        .section-text {
            font-size: 13px;
            color: #374151;
            margin: 0 0 10px 0;
        }
        .info-grid {
            width: 100%;
            border-collapse: collapse;
            margin: 6px 0 4px 0;
            font-size: 12px;
        }
        .info-grid th,
        .info-grid td {
            padding: 6px 8px;
        }
        .info-grid th {
            text-align: left;
            width: 40%;
            color: #6b7280;
            font-weight: 500;
            background-color: #f9fafb;
        }
        .info-grid td {
            text-align: left;
            color: #111827;
            background-color: #ffffff;
        }
        .pill {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            background-color: #eff6ff;
            color: #1d4ed8;
            font-size: 11px;
            font-weight: 600;
        }
        .footer {
            margin-top: 22px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
            font-size: 11px;
            color: #6b7280;
            text-align: center;
        }
        .code {
            font-family: "SFMono-Regular", Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            background-color: #f3f4f6;
            border-radius: 4px;
            padding: 2px 6px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="brand-name">{{ $companyName ?? 'TmcsSmart' }}</div>
            <p class="brand-tagline">Email Configuration Test</p>
            <div class="badge">Delivery successful</div>

            <h1 class="title">Your email settings are working</h1>
            <p class="subtitle">
                This test message confirms that outgoing email from your TmcsSmart system is configured correctly.
            </p>
        </div>

        <div class="section">
            <h2 class="section-title">What this means</h2>
            <p class="section-text">
                The system was able to connect to your email server and deliver this message to
                <span class="code">{{ $recipient ?? 'N/A' }}</span>. You can now safely use features like password
                reset, customer notifications, and PDF report sharing via email.
            </p>
        </div>

        <div class="section">
            <h2 class="section-title">Connection details (summary)</h2>
            <table class="info-grid" role="presentation">
                <tr>
                    <th>Mailer</th>
                    <td>
                        <span class="pill">{{ strtoupper($details['mailer'] ?? 'SMTP') }}</span>
                    </td>
                </tr>
                <tr>
                    <th>From address</th>
                    <td>{{ $details['from_address'] ?? '—' }}</td>
                </tr>
                <tr>
                    <th>From name</th>
                    <td>{{ $details['from_name'] ?? '—' }}</td>
                </tr>
                @if(!empty($details['host']))
                    <tr>
                        <th>SMTP Host</th>
                        <td>{{ $details['host'] }}</td>
                    </tr>
                @endif
                @if(!empty($details['port']))
                    <tr>
                        <th>SMTP Port</th>
                        <td>{{ $details['port'] }}</td>
                    </tr>
                @endif
                @if(!empty($details['encryption']))
                    <tr>
                        <th>Encryption</th>
                        <td>{{ strtoupper($details['encryption']) }}</td>
                    </tr>
                @endif
                <tr>
                    <th>Environment</th>
                    <td>{{ $details['environment'] ?? 'production' }}</td>
                </tr>
                <tr>
                    <th>Sent at</th>
                    <td>{{ $details['sent_at'] ?? now()->toDateTimeString() }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h2 class="section-title">Next steps</h2>
            <p class="section-text">
                If you plan to use email for customer communication (invoices, quotations, statements, etc.),
                we recommend:
            </p>
            <ul class="section-text" style="padding-left: 18px; margin-top: 4px;">
                <li>Adding this <span class="code">{{ $details['from_address'] ?? 'no-reply@example.com' }}</span> to your address book.</li>
                <li>Checking that messages don’t land in the spam folder.</li>
                <li>Enabling email sending for key reports inside TmcsSmart (sales, purchases, financial statements).</li>
            </ul>
        </div>

        <div class="footer">
            <p>This is an automated test message from {{ $companyName ?? 'TmcsSmart' }}. No action is required.</p>
            <p style="margin-top: 6px;">© {{ date('Y') }} {{ $companyName ?? 'TmcsSmart' }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>





