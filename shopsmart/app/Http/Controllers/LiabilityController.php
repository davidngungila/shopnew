<?php

namespace App\Http\Controllers;

use App\Models\Liability;
use App\Models\ChartOfAccount;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LiabilityController extends Controller
{
    public function index(Request $request)
    {
        $query = Liability::with(['account', 'supplier', 'user']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $liabilities = $query->latest()->paginate(20)->appends($request->query());

        $allLiabilitiesForStats = (clone $query)->get();
        $totalLiabilities = $allLiabilitiesForStats->sum('outstanding_balance');
        $activeLiabilities = $allLiabilitiesForStats->where('status', 'active')->sum('outstanding_balance');
        $overdueLiabilities = $allLiabilitiesForStats->where('status', 'overdue')->sum('outstanding_balance');

        return view('liabilities.index', compact('liabilities', 'totalLiabilities', 'activeLiabilities', 'overdueLiabilities'));
    }

    public function create()
    {
        $accounts = ChartOfAccount::where('account_type', 'liability')
            ->where('is_active', true)
            ->orderBy('account_name')
            ->get();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        return view('liabilities.create', compact('accounts', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:loan,credit,payable,other',
            'principal_amount' => 'required|numeric|min:0',
            'interest_rate' => 'nullable|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'due_date' => 'nullable|date|after:start_date',
            'description' => 'nullable|string',
            'account_id' => 'nullable|exists:chart_of_accounts,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        $validated['liability_number'] = 'LIAB-' . strtoupper(uniqid());
        $validated['outstanding_balance'] = $validated['principal_amount'];
        $validated['status'] = 'active';
        $validated['user_id'] = auth()->id();

        Liability::create($validated);
        return redirect()->route('liabilities.index')->with('success', 'Liability created successfully.');
    }

    public function show(Liability $liability)
    {
        $liability->load(['account', 'supplier', 'user']);
        return view('liabilities.show', compact('liability'));
    }

    public function edit(Liability $liability)
    {
        $accounts = ChartOfAccount::where('account_type', 'liability')
            ->where('is_active', true)
            ->orderBy('account_name')
            ->get();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        return view('liabilities.edit', compact('liability', 'accounts', 'suppliers'));
    }

    public function update(Request $request, Liability $liability)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:loan,credit,payable,other',
            'principal_amount' => 'required|numeric|min:0',
            'outstanding_balance' => 'required|numeric|min:0',
            'interest_rate' => 'nullable|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'due_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:active,paid,overdue',
            'description' => 'nullable|string',
            'account_id' => 'nullable|exists:chart_of_accounts,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        $liability->update($validated);
        return redirect()->route('liabilities.index')->with('success', 'Liability updated successfully.');
    }

    public function destroy(Liability $liability)
    {
        $liability->delete();
        return redirect()->route('liabilities.index')->with('success', 'Liability deleted successfully.');
    }

    public function pdf(Request $request)
    {
        $query = Liability::with(['account', 'supplier', 'user']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $liabilities = $query->latest()->get();
        $totalLiabilities = $liabilities->sum('outstanding_balance');
        $activeLiabilities = $liabilities->where('status', 'active')->sum('outstanding_balance');
        $overdueLiabilities = $liabilities->where('status', 'overdue')->sum('outstanding_balance');

        $filters = $request->only(['type', 'status']);

        $pdf = Pdf::loadView('liabilities.pdf.index', compact('liabilities', 'totalLiabilities', 'activeLiabilities', 'overdueLiabilities', 'filters'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('liabilities-report-' . now()->format('Y-m-d') . '.pdf');
    }
}
