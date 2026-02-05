<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with('user');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('employee_id', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
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

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Statistics
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('is_active', true)->count();
        $totalSalary = Employee::where('is_active', true)->sum('salary');
        $averageSalary = Employee::where('is_active', true)->avg('salary') ?? 0;

        // Role distribution
        $roleDistribution = Employee::selectRaw('role, COUNT(*) as count')
            ->groupBy('role')
            ->get()
            ->pluck('count', 'role');

        $employees = $query->latest()->paginate(20);
        $roles = Employee::distinct()->pluck('role')->filter();

        return view('employees.index', compact('employees', 'totalEmployees', 'activeEmployees', 'totalSalary', 'averageSalary', 'roleDistribution', 'roles'));
    }

    public function create()
    {
        $users = User::whereDoesntHave('employee')->get();
        $roles = ['Manager', 'Sales', 'Inventory', 'Accountant', 'Cashier', 'Admin'];
        return view('employees.create', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:employees,user_id',
            'employee_id' => 'required|string|max:50|unique:employees,employee_id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'role' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        Employee::create($validated);
        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee)
    {
        $employee->load('user');
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $users = User::all();
        $roles = ['Manager', 'Sales', 'Inventory', 'Accountant', 'Cashier', 'Admin'];
        return view('employees.edit', compact('employee', 'users', 'roles'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:employees,user_id,' . $employee->id,
            'employee_id' => 'required|string|max:50|unique:employees,employee_id,' . $employee->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'role' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $employee->update($validated);
        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }

    public function roles()
    {
        $roles = Employee::selectRaw('role, COUNT(*) as count, AVG(salary) as avg_salary, SUM(salary) as total_salary')
            ->where('is_active', true)
            ->groupBy('role')
            ->get();

        return view('employees.roles', compact('roles'));
    }

    public function attendance()
    {
        return view('employees.attendance');
    }
}
