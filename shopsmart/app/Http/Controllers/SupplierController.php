<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::withCount('purchases');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Statistics
        $totalSuppliers = Supplier::count();
        $activeSuppliers = Supplier::where('is_active', true)->count();
        $totalBalance = Supplier::sum('balance');
        $totalPurchases = Supplier::with('purchases')->get()->sum(function($supplier) {
            return $supplier->purchases->sum('total');
        });

        // Top suppliers by purchase value
        $topSuppliers = Supplier::with('purchases')
            ->get()
            ->map(function($supplier) {
                $supplier->total_purchase_value = $supplier->purchases->sum('total');
                $supplier->purchase_count = $supplier->purchases->count();
                return $supplier;
            })
            ->sortByDesc('total_purchase_value')
            ->take(5);

        // Monthly purchase trend (last 12 months)
        $monthlyPurchases = \App\Models\Purchase::where('purchase_date', '>=', now()->subMonths(12))
            ->selectRaw('DATE_FORMAT(purchase_date, "%Y-%m") as month, COUNT(*) as count, SUM(total) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Suppliers by status
        $suppliersByStatus = Supplier::selectRaw('is_active, COUNT(*) as count')
            ->groupBy('is_active')
            ->get();

        // Recent purchases by suppliers (last 30 days)
        $recentPurchases = \App\Models\Purchase::where('purchase_date', '>=', now()->subDays(30))
            ->selectRaw('supplier_id, COUNT(*) as purchase_count, SUM(total) as total_spent')
            ->whereNotNull('supplier_id')
            ->groupBy('supplier_id')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get()
            ->load('supplier');

        $suppliers = $query->latest()->paginate(20);

        return view('suppliers.index', compact(
            'suppliers', 'totalSuppliers', 'activeSuppliers', 'totalBalance', 
            'totalPurchases', 'topSuppliers', 'monthlyPurchases', 
            'suppliersByStatus', 'recentPurchases'
        ));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:suppliers,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'balance' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        Supplier::create($validated);
        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load(['purchases' => function($query) {
            $query->latest()->limit(10);
        }, 'purchases.items.product']);

        // Statistics
        $totalPurchases = $supplier->purchases->count();
        $totalPurchaseValue = $supplier->purchases->sum('total');
        $totalPaid = $supplier->purchases->sum('paid_amount');
        $totalDue = $supplier->purchases->sum('due_amount');
        $lastPurchase = $supplier->purchases->first();

        return view('suppliers.show', compact('supplier', 'totalPurchases', 'totalPurchaseValue', 'totalPaid', 'totalDue', 'lastPurchase'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:suppliers,email,' . $supplier->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'balance' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $supplier->update($validated);
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->purchases()->count() > 0) {
            return redirect()->route('suppliers.index')->with('error', 'Cannot delete supplier with existing purchases.');
        }

        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
