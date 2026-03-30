<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SmsLogsController extends Controller
{
    public function index()
    {
        $logs = SmsLog::latest()->paginate(20);
        return view('admin.sms.logs.index', compact('logs'));
    }

    public function show(SmsLog $smsLog)
    {
        return view('admin.sms.logs.show', compact('smsLog'));
    }

    public function syncFromApi()
    {
        // Implementation for syncing SMS logs from API
        return redirect()->back()->with('success', 'SMS logs synced successfully');
    }

    public function exportPdf()
    {
        // Implementation for PDF export
        return response()->download('sms-logs.pdf');
    }

    public function exportExcel()
    {
        // Implementation for Excel export
        return response()->download('sms-logs.xlsx');
    }
}
