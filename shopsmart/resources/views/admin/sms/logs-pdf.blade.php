<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $documentTitle }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #009245;
            padding-bottom: 20px;
        }
        .header img {
            max-width: 200px;
            margin-bottom: 10px;
        }
        .header h1 {
            color: #009245;
            margin: 10px 0 5px 0;
            font-size: 24px;
        }
        .header p {
            color: #666;
            margin: 5px 0;
            font-size: 14px;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        .stat-item {
            text-align: center;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #009245;
        }
        .stat-label {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        .filters {
            background: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .filter-item {
            margin: 5px 0;
        }
        .filter-label {
            font-weight: bold;
            color: #495057;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #009245;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .status-delivered {
            color: #28a745;
            font-weight: bold;
        }
        .status-pending {
            color: #ffc107;
            font-weight: bold;
        }
        .status-rejected {
            color: #dc3545;
            font-weight: bold;
        }
        .status-accepted {
            color: #007bff;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .message-content {
            max-width: 200px;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        @if($headerBase64)
            <img src="data:image/png;base64,{{ $headerBase64 }}" alt="Company Logo">
        @endif
        <h1>SMS Communication Logs Report</h1>
        <p>Generated on: {{ $generatedAt }}</p>
    </div>

    <!-- Statistics -->
    <div class="stats">
        <div class="stat-item">
            <div class="stat-number">{{ $stats['total'] }}</div>
            <div class="stat-label">Total SMS</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $stats['success'] }}</div>
            <div class="stat-label">Successful</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $stats['failed'] }}</div>
            <div class="stat-label">Failed</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $balance['balance'] ?? 0 }}</div>
            <div class="stat-label">SMS Balance</div>
        </div>
    </div>

    <!-- Filters Applied -->
    @if(count($filters) > 0)
    <div class="filters">
        <h3>Filters Applied:</h3>
        @foreach($filters as $key => $value)
            @if($value)
                <div class="filter-item">
                    <span class="filter-label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span> 
                    {{ $value }}
                </div>
            @endif
        @endforeach
    </div>
    @endif

    <!-- Logs Table -->
    <table>
        <thead>
            <tr>
                <th>Message ID</th>
                <th>Reference</th>
                <th>From</th>
                <th>To</th>
                <th>Message</th>
                <th>Status</th>
                <th>Sent At</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td>{{ $log->message_id ?? 'N/A' }}</td>
                <td>{{ $log->reference ?? 'N/A' }}</td>
                <td>{{ $log->from ?? 'N/A' }}</td>
                <td>{{ $log->to }}</td>
                <td class="message-content">{{ Str::limit($log->message ?? 'N/A', 100) }}</td>
                <td>
                    @switch($log->status_group_name)
                        @case('DELIVERED')
                            <span class="status-delivered">Delivered</span>
                        @case('PENDING')
                            <span class="status-pending">Pending</span>
                        @case('REJECTED')
                            <span class="status-rejected">Rejected</span>
                        @case('ACCEPTED')
                            <span class="status-accepted">Accepted</span>
                        @default
                            <span>{{ $log->status_group_name ?? 'Unknown' }}</span>
                    @endswitch
                </td>
                <td>{{ $log->sent_at ? $log->sent_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                <td>{{ $log->user ? $log->user->name : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Report generated by ShopSmart SMS Management System</p>
        <p>Page {{ request('page', 1) }} of {{ $logs->lastPage() }}</p>
    </div>
</body>
</html>
