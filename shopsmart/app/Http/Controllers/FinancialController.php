<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Transaction;
use App\Models\ChartOfAccount;
use App\Models\Capital;
use App\Models\Liability;
use App\Models\BankReconciliation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialController extends Controller
{
    public function index()
    {
        // Total Revenue (Sales)
        $totalRevenue = Sale::where('status', 'completed')->sum('total');
        
        // Total Expenses
        $totalExpenses = Expense::sum('amount');
        
        // Total Purchases (Cost of Goods Sold)
        $totalPurchases = Purchase::sum('total');
        
        // Net Profit / Loss
        $netProfit = $totalRevenue - $totalExpenses - $totalPurchases;
        
        // Total Assets (Cash + Bank + Accounts Receivable)
        $cashBalance = ChartOfAccount::where('account_type', 'Asset')
            ->where('account_name', 'like', '%Cash%')
            ->sum('opening_balance');
        
        $bankBalance = ChartOfAccount::where('account_type', 'Asset')
            ->where('account_name', 'like', '%Bank%')
            ->sum('opening_balance');
            
        $accountsReceivable = Sale::where('status', 'completed')
            ->where('payment_status', '!=', 'paid')
            ->sum('total');
        
        $totalAssets = $cashBalance + $bankBalance + $accountsReceivable;
        
        // Total Liabilities (Accounts Payable + Loans)
        $accountsPayable = Purchase::where('payment_status', '!=', 'paid')
            ->sum('total');
            
        $totalLoans = Liability::where('type', 'loan')
            ->sum('outstanding_balance');
            
        $totalLiabilities = $accountsPayable + $totalLoans;
        
        // Recent Transactions
        $recentTransactions = Transaction::latest()->limit(10)->get();
        
        // Recent Invoices
        $recentInvoices = Sale::where('status', 'completed')
            ->latest()
            ->limit(5)
            ->get();
        
        // Outstanding Payments
        $outstandingPayments = Sale::where('payment_status', '!=', 'paid')
            ->get();
        
        // Monthly Income vs Expense Chart (last 12 months)
        $monthlyData = Sale::where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(12))
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(total) as income')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        $monthlyExpenses = Expense::where('created_at', '>=', now()->subMonths(12))
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(amount) as expenses')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Merge income and expenses data
        $monthlyIncomeExpense = [];
        foreach ($monthlyData as $data) {
            $monthlyIncomeExpense[$data->month] = [
                'income' => $data->income,
                'expenses' => 0
            ];
        }
        
        foreach ($monthlyExpenses as $expense) {
            if (isset($monthlyIncomeExpense[$expense->month])) {
                $monthlyIncomeExpense[$expense->month]['expenses'] = $expense->expenses;
            } else {
                $monthlyIncomeExpense[$expense->month] = [
                    'income' => 0,
                    'expenses' => $expense->expenses
                ];
            }
        }
        
        // Top Expense Categories
        $topExpenseCategories = Expense::select('expense_category_id', 
                DB::raw('SUM(amount) as total'),
                DB::raw('COUNT(*) as count'))
            ->with('expenseCategory')
            ->groupBy('expense_category_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();
        
        return view('financial.index', compact(
            'totalRevenue',
            'totalExpenses',
            'totalPurchases',
            'netProfit',
            'totalAssets',
            'totalLiabilities',
            'cashBalance',
            'bankBalance',
            'accountsReceivable',
            'accountsPayable',
            'recentTransactions',
            'recentInvoices',
            'outstandingPayments',
            'monthlyIncomeExpense',
            'topExpenseCategories'
        ));
    }

    public function income()
    {
        $income = \App\Models\Sale::where('status', 'completed')
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->paginate(30);
        return view('financial.income', compact('income'));
    }

    public function profitLoss()
    {
        $sales = \App\Models\Sale::where('status', 'completed')->sum('total');
        $purchases = \App\Models\Purchase::sum('total');
        $expenses = \App\Models\Expense::sum('amount');
        $profit = $sales - $purchases - $expenses;
        
        return view('financial.profit-loss', compact('sales', 'purchases', 'expenses', 'profit'));
    }
}
