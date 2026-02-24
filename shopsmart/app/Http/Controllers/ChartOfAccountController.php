<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ChartOfAccountController extends Controller
{
    public function index(Request $request)
    {
        $query = ChartOfAccount::with('parentAccount');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('account_code', 'like', "%{$search}%")
                  ->orWhere('account_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('account_type')) {
            $query->where('account_type', $request->account_type);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active == '1');
        }

        $accounts = $query->orderBy('account_code')->paginate(50)->appends($request->query());
        $accountTypes = ['asset', 'liability', 'equity', 'revenue', 'expense'];
        $totalAccounts = (clone $query)->count();
        $totalBalance = (clone $query)->sum('current_balance');

        return view('chart-of-accounts.index', compact('accounts', 'accountTypes', 'totalAccounts', 'totalBalance'));
    }

    public function create()
    {
        $parentAccounts = ChartOfAccount::where('is_active', true)->orderBy('account_name')->get();
        $accountTypes = ['asset', 'liability', 'equity', 'revenue', 'expense'];
        return view('chart-of-accounts.create', compact('parentAccounts', 'accountTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_code' => 'required|string|max:50|unique:chart_of_accounts,account_code',
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|in:asset,liability,equity,revenue,expense',
            'account_category' => 'nullable|string',
            'opening_balance' => 'nullable|numeric',
            'description' => 'nullable|string',
            'parent_account_id' => 'nullable|exists:chart_of_accounts,id',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['current_balance'] = $validated['opening_balance'] ?? 0;

        ChartOfAccount::create($validated);
        return redirect()->route('chart-of-accounts.index')->with('success', 'Account created successfully.');
    }

    public function show(ChartOfAccount $chartOfAccount)
    {
        $chartOfAccount->load(['parentAccount', 'childAccounts']);
        return view('chart-of-accounts.show', compact('chartOfAccount'));
    }

    public function edit(ChartOfAccount $chartOfAccount)
    {
        $parentAccounts = ChartOfAccount::where('is_active', true)
            ->where('id', '!=', $chartOfAccount->id)
            ->orderBy('account_name')
            ->get();
        $accountTypes = ['asset', 'liability', 'equity', 'revenue', 'expense'];
        return view('chart-of-accounts.edit', compact('chartOfAccount', 'parentAccounts', 'accountTypes'));
    }

    public function update(Request $request, ChartOfAccount $chartOfAccount)
    {
        $validated = $request->validate([
            'account_code' => 'required|string|max:50|unique:chart_of_accounts,account_code,' . $chartOfAccount->id,
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|in:asset,liability,equity,revenue,expense',
            'account_category' => 'nullable|string',
            'opening_balance' => 'nullable|numeric',
            'description' => 'nullable|string',
            'parent_account_id' => 'nullable|exists:chart_of_accounts,id',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $chartOfAccount->update($validated);
        return redirect()->route('chart-of-accounts.index')->with('success', 'Account updated successfully.');
    }

    public function destroy(ChartOfAccount $chartOfAccount)
    {
        if ($chartOfAccount->childAccounts()->count() > 0) {
            return redirect()->route('chart-of-accounts.index')->with('error', 'Cannot delete account with child accounts.');
        }

        $chartOfAccount->delete();
        return redirect()->route('chart-of-accounts.index')->with('success', 'Account deleted successfully.');
    }

    public function pdf(Request $request)
    {
        $query = ChartOfAccount::with('parentAccount');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('account_code', 'like', "%{$search}%")
                  ->orWhere('account_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('account_type')) {
            $query->where('account_type', $request->account_type);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active == '1');
        }

        $accounts = $query->orderBy('account_code')->get();
        $accountTypes = ['asset', 'liability', 'equity', 'revenue', 'expense'];
        $totalAccounts = $accounts->count();
        $totalBalance = $accounts->sum('current_balance');

        $pdf = Pdf::loadView('chart-of-accounts.pdf.index', compact('accounts', 'accountTypes', 'totalAccounts', 'totalBalance'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('chart-of-accounts-' . now()->format('Y-m-d') . '.pdf');
    }
}
