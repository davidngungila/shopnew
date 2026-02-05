<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotationReportController extends Controller
{
    public function overview(Request $request)
    {
        $query = Quotation::query();

        // Date filters
        if ($request->filled('date_from')) {
            $query->whereDate('quotation_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('quotation_date', '<=', $request->date_to);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $totalQuotations = Quotation::count();
        $pendingQuotations = Quotation::where('status', 'pending')->count();
        $approvedQuotations = Quotation::where('status', 'approved')->count();
        $rejectedQuotations = Quotation::where('status', 'rejected')->count();
        $convertedQuotations = Quotation::whereNotNull('converted_to_sale_id')->count();
        $expiredQuotations = Quotation::where('status', 'expired')
            ->orWhere(function($q) {
                $q->where('status', 'pending')
                  ->where('expiry_date', '<', now());
            })
            ->count();

        $totalValue = Quotation::sum('total');
        $convertedValue = Quotation::whereNotNull('converted_to_sale_id')->sum('total');
        $pendingValue = Quotation::where('status', 'pending')->sum('total');
        $approvedValue = Quotation::where('status', 'approved')->sum('total');

        $conversionRate = $totalQuotations > 0 ? ($convertedQuotations / $totalQuotations) * 100 : 0;
        $approvalRate = $totalQuotations > 0 ? ($approvedQuotations / $totalQuotations) * 100 : 0;

        // Monthly statistics (last 12 months)
        $monthlyStats = Quotation::select(
            DB::raw('DATE_FORMAT(quotation_date, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(total) as total'),
            DB::raw('SUM(CASE WHEN converted_to_sale_id IS NOT NULL THEN 1 ELSE 0 END) as converted_count')
        )
        ->where('quotation_date', '>=', now()->subMonths(12))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Status breakdown
        $statusBreakdown = Quotation::selectRaw('status, COUNT(*) as count, SUM(total) as total')
            ->groupBy('status')
            ->get();

        // Top customers by quotation value
        $topCustomers = Quotation::select('customers.name', 'customers.email', 'customers.phone', 
                DB::raw('COUNT(quotations.id) as count'), 
                DB::raw('SUM(quotations.total) as total'),
                DB::raw('SUM(CASE WHEN quotations.converted_to_sale_id IS NOT NULL THEN 1 ELSE 0 END) as converted_count')
            )
            ->join('customers', 'quotations.customer_id', '=', 'customers.id')
            ->groupBy('customers.id', 'customers.name', 'customers.email', 'customers.phone')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // Recent quotations
        $recentQuotations = Quotation::with('customer')
            ->latest()
            ->limit(10)
            ->get();

        // Average quotation value
        $averageQuotationValue = Quotation::avg('total') ?? 0;

        return view('quotations.reports.overview', compact(
            'totalQuotations',
            'pendingQuotations',
            'approvedQuotations',
            'rejectedQuotations',
            'convertedQuotations',
            'expiredQuotations',
            'totalValue',
            'convertedValue',
            'pendingValue',
            'approvedValue',
            'conversionRate',
            'approvalRate',
            'monthlyStats',
            'statusBreakdown',
            'topCustomers',
            'recentQuotations',
            'averageQuotationValue'
        ));
    }
}
