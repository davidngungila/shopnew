<?php

namespace App\Http\Controllers;

use App\Models\BankReconciliation;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;

class BankReconciliationController extends Controller
{
    public function index(Request $request)
    {
        $query = BankReconciliation::with(['account', 'user']);

        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reconciliations = $query->latest()->paginate(20);
        $accounts = ChartOfAccount::where('account_type', 'asset')
            ->where('account_category', 'current_asset')
            ->where('is_active', true)
            ->orderBy('account_name')
            ->get();

        return view('bank-reconciliations.index', compact('reconciliations', 'accounts'));
    }

    public function create()
    {
        $accounts = ChartOfAccount::where('account_type', 'asset')
            ->where('account_category', 'current_asset')
            ->where('is_active', true)
            ->orderBy('account_name')
            ->get();
        return view('bank-reconciliations.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:chart_of_accounts,id',
            'statement_date' => 'required|date',
            'bank_balance' => 'required|numeric',
            'book_balance' => 'required|numeric',
            'deposits_in_transit' => 'nullable|numeric',
            'outstanding_checks' => 'nullable|numeric',
            'bank_charges' => 'nullable|numeric',
            'interest_earned' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $validated['reconciliation_number'] = 'REC-' . strtoupper(uniqid());
        $validated['user_id'] = auth()->id();

        // Calculate adjusted balance
        $validated['adjusted_balance'] = $validated['book_balance'] 
            + ($validated['deposits_in_transit'] ?? 0)
            - ($validated['outstanding_checks'] ?? 0)
            - ($validated['bank_charges'] ?? 0)
            + ($validated['interest_earned'] ?? 0);

        // Determine status
        $difference = abs($validated['adjusted_balance'] - $validated['bank_balance']);
        $validated['status'] = $difference < 0.01 ? 'reconciled' : 'discrepancy';

        BankReconciliation::create($validated);
        return redirect()->route('bank-reconciliations.index')->with('success', 'Bank reconciliation created successfully.');
    }

    public function show(BankReconciliation $bankReconciliation)
    {
        $bankReconciliation->load(['account', 'user']);
        return view('bank-reconciliations.show', compact('bankReconciliation'));
    }

    public function edit(BankReconciliation $bankReconciliation)
    {
        $accounts = ChartOfAccount::where('account_type', 'asset')
            ->where('account_category', 'current_asset')
            ->where('is_active', true)
            ->orderBy('account_name')
            ->get();
        return view('bank-reconciliations.edit', compact('bankReconciliation', 'accounts'));
    }

    public function update(Request $request, BankReconciliation $bankReconciliation)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:chart_of_accounts,id',
            'statement_date' => 'required|date',
            'bank_balance' => 'required|numeric',
            'book_balance' => 'required|numeric',
            'deposits_in_transit' => 'nullable|numeric',
            'outstanding_checks' => 'nullable|numeric',
            'bank_charges' => 'nullable|numeric',
            'interest_earned' => 'nullable|numeric',
            'status' => 'required|in:pending,reconciled,discrepancy',
            'notes' => 'nullable|string',
        ]);

        // Calculate adjusted balance
        $validated['adjusted_balance'] = $validated['book_balance'] 
            + ($validated['deposits_in_transit'] ?? 0)
            - ($validated['outstanding_checks'] ?? 0)
            - ($validated['bank_charges'] ?? 0)
            + ($validated['interest_earned'] ?? 0);

        $bankReconciliation->update($validated);
        return redirect()->route('bank-reconciliations.index')->with('success', 'Bank reconciliation updated successfully.');
    }

    public function destroy(BankReconciliation $bankReconciliation)
    {
        $bankReconciliation->delete();
        return redirect()->route('bank-reconciliations.index')->with('success', 'Bank reconciliation deleted successfully.');
    }
}
