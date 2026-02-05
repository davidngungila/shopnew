<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Expense;
use App\Models\Purchase;
use App\Models\ChartOfAccount;
use App\Models\Capital;
use App\Models\Liability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinancialStatementController extends Controller
{
    public function profitLoss(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        // Revenue
        $revenue = Sale::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('status', 'completed')
            ->sum('total');

        // Cost of Goods Sold (COGS)
        $cogs = Purchase::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('status', 'completed')
            ->sum('total');

        // Gross Profit
        $grossProfit = $revenue - $cogs;

        // Operating Expenses
        $operatingExpenses = Expense::whereBetween('expense_date', [$dateFrom, $dateTo])
            ->sum('amount');

        // Net Profit
        $netProfit = $grossProfit - $operatingExpenses;

        // Expense breakdown
        $expenseBreakdown = Expense::select('category', DB::raw('SUM(amount) as total'))
            ->whereBetween('expense_date', [$dateFrom, $dateTo])
            ->groupBy('category')
            ->get();

        return view('financial-statements.profit-loss', compact(
            'revenue', 'cogs', 'grossProfit', 'operatingExpenses', 'netProfit',
            'expenseBreakdown', 'dateFrom', 'dateTo'
        ));
    }

    public function balanceSheet(Request $request)
    {
        $asOfDate = $request->get('as_of_date', now()->toDateString());

        // Assets
        $currentAssets = ChartOfAccount::where('account_type', 'asset')
            ->where('account_category', 'current_asset')
            ->where('is_active', true)
            ->sum('current_balance');

        $fixedAssets = ChartOfAccount::where('account_type', 'asset')
            ->where('account_category', 'fixed_asset')
            ->where('is_active', true)
            ->sum('current_balance');

        $totalAssets = $currentAssets + $fixedAssets;

        // Liabilities
        $currentLiabilities = ChartOfAccount::where('account_type', 'liability')
            ->where('account_category', 'current_liability')
            ->where('is_active', true)
            ->sum('current_balance');

        $longTermLiabilities = Liability::where('status', 'active')
            ->sum('outstanding_balance');

        $totalLiabilities = $currentLiabilities + $longTermLiabilities;

        // Equity
        $capital = Capital::where('type', 'contribution')->sum('amount') - 
                   Capital::where('type', 'withdrawal')->sum('amount');

        $retainedEarnings = ChartOfAccount::where('account_type', 'equity')
            ->where('is_active', true)
            ->sum('current_balance');

        $totalEquity = $capital + $retainedEarnings;

        return view('financial-statements.balance-sheet', compact(
            'currentAssets', 'fixedAssets', 'totalAssets',
            'currentLiabilities', 'longTermLiabilities', 'totalLiabilities',
            'capital', 'retainedEarnings', 'totalEquity', 'asOfDate'
        ));
    }

    public function trialBalance(Request $request)
    {
        $asOfDate = $request->get('as_of_date', now()->toDateString());

        $accounts = ChartOfAccount::where('is_active', true)
            ->orderBy('account_type')
            ->orderBy('account_code')
            ->get()
            ->map(function($account) {
                return [
                    'code' => $account->account_code,
                    'name' => $account->account_name,
                    'type' => $account->account_type,
                    'debit' => $account->account_type == 'asset' || $account->account_type == 'expense' ? $account->current_balance : 0,
                    'credit' => $account->account_type == 'liability' || $account->account_type == 'equity' || $account->account_type == 'revenue' ? $account->current_balance : 0,
                ];
            });

        $totalDebit = $accounts->sum('debit');
        $totalCredit = $accounts->sum('credit');

        return view('financial-statements.trial-balance', compact('accounts', 'totalDebit', 'totalCredit', 'asOfDate'));
    }
}
