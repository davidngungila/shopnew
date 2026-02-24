<?php

namespace App\Http\Controllers;

use App\Models\Capital;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CapitalController extends Controller
{
    public function index(Request $request)
    {
        $query = Capital::with(['account', 'user']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        $capitals = $query->latest()->paginate(20)->appends($request->query());

        $allCapitalsForStats = (clone $query)->get();
        $totalContributions = $allCapitalsForStats->where('type', 'contribution')->sum('amount');
        $totalWithdrawals = $allCapitalsForStats->where('type', 'withdrawal')->sum('amount');
        $netCapital = $totalContributions - $totalWithdrawals;

        return view('capital.index', compact('capitals', 'totalContributions', 'totalWithdrawals', 'netCapital'));
    }

    public function create()
    {
        $accounts = ChartOfAccount::where('account_type', 'equity')
            ->where('is_active', true)
            ->orderBy('account_name')
            ->get();
        return view('capital.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:contribution,withdrawal,profit,loss',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'account_id' => 'nullable|exists:chart_of_accounts,id',
            'transaction_date' => 'required|date',
            'reference' => 'nullable|string|max:255',
        ]);

        $validated['transaction_number'] = 'CAP-' . strtoupper(uniqid());
        $validated['user_id'] = auth()->id();

        Capital::create($validated);
        return redirect()->route('capital.index')->with('success', 'Capital transaction created successfully.');
    }

    public function show(Capital $capital)
    {
        $capital->load(['account', 'user']);
        return view('capital.show', compact('capital'));
    }

    public function edit(Capital $capital)
    {
        $accounts = ChartOfAccount::where('account_type', 'equity')
            ->where('is_active', true)
            ->orderBy('account_name')
            ->get();
        return view('capital.edit', compact('capital', 'accounts'));
    }

    public function update(Request $request, Capital $capital)
    {
        $validated = $request->validate([
            'type' => 'required|in:contribution,withdrawal,profit,loss',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'account_id' => 'nullable|exists:chart_of_accounts,id',
            'transaction_date' => 'required|date',
            'reference' => 'nullable|string|max:255',
        ]);

        $capital->update($validated);
        return redirect()->route('capital.index')->with('success', 'Capital transaction updated successfully.');
    }

    public function destroy(Capital $capital)
    {
        $capital->delete();
        return redirect()->route('capital.index')->with('success', 'Capital transaction deleted successfully.');
    }

    public function pdf(Request $request)
    {
        $query = Capital::with(['account', 'user']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        $capitals = $query->latest()->get();
        $totalContributions = $capitals->where('type', 'contribution')->sum('amount');
        $totalWithdrawals = $capitals->where('type', 'withdrawal')->sum('amount');
        $netCapital = $totalContributions - $totalWithdrawals;

        $filters = $request->only(['type', 'date_from', 'date_to']);

        $pdf = Pdf::loadView('capital.pdf.index', compact('capitals', 'totalContributions', 'totalWithdrawals', 'netCapital', 'filters'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('capital-report-' . now()->format('Y-m-d') . '.pdf');
    }
}
