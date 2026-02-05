<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->paginate(20);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        Customer::create($validated);
        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        $customer->load('sales');
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $customer->update($validated);
        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    public function loyalty(Request $request)
    {
        $query = Customer::with('sales');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Points filter
        if ($request->filled('points_min')) {
            $query->where('loyalty_points', '>=', $request->points_min);
        }

        if ($request->filled('points_max')) {
            $query->where('loyalty_points', '<=', $request->points_max);
        }

        // Sort
        $sortBy = $request->get('sort', 'loyalty_points');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Statistics
        $totalCustomers = Customer::count();
        $customersWithPoints = Customer::where('loyalty_points', '>', 0)->count();
        $totalPointsIssued = Customer::sum('loyalty_points');
        $totalPointsRedeemed = 0; // Can be tracked separately if needed
        $averagePoints = Customer::where('loyalty_points', '>', 0)->avg('loyalty_points') ?? 0;

        // Top customers by points
        $topCustomers = Customer::orderBy('loyalty_points', 'desc')
            ->limit(10)
            ->get();

        // Points distribution
        $pointsDistribution = [
            'bronze' => Customer::where('loyalty_points', '>=', 0)->where('loyalty_points', '<', 100)->count(),
            'silver' => Customer::where('loyalty_points', '>=', 100)->where('loyalty_points', '<', 500)->count(),
            'gold' => Customer::where('loyalty_points', '>=', 500)->where('loyalty_points', '<', 1000)->count(),
            'platinum' => Customer::where('loyalty_points', '>=', 1000)->count(),
        ];

        $customers = $query->paginate(20);

        return view('customers.loyalty', compact('customers', 'totalCustomers', 'customersWithPoints', 'totalPointsIssued', 'totalPointsRedeemed', 'averagePoints', 'topCustomers', 'pointsDistribution'));
    }
}
