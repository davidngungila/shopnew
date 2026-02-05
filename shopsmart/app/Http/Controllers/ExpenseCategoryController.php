<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ExpenseCategory::with('account');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active == '1');
        }

        $categories = $query->orderBy('sort_order')->orderBy('name')->paginate(20);
        $totalCategories = ExpenseCategory::count();
        $activeCategories = ExpenseCategory::where('is_active', true)->count();

        return view('expense-categories.index', compact('categories', 'totalCategories', 'activeCategories'));
    }

    public function create()
    {
        $accounts = ChartOfAccount::where('account_type', 'expense')
            ->where('is_active', true)
            ->orderBy('account_name')
            ->get();
        return view('expense-categories.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name',
            'code' => 'nullable|string|max:50|unique:expense_categories,code',
            'description' => 'nullable|string',
            'account_id' => 'nullable|exists:chart_of_accounts,id',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        ExpenseCategory::create($validated);
        return redirect()->route('expense-categories.index')->with('success', 'Category created successfully.');
    }

    public function show(ExpenseCategory $expenseCategory)
    {
        $expenseCategory->load('account');
        return view('expense-categories.show', compact('expenseCategory'));
    }

    public function edit(ExpenseCategory $expenseCategory)
    {
        $accounts = ChartOfAccount::where('account_type', 'expense')
            ->where('is_active', true)
            ->orderBy('account_name')
            ->get();
        return view('expense-categories.edit', compact('expenseCategory', 'accounts'));
    }

    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name,' . $expenseCategory->id,
            'code' => 'nullable|string|max:50|unique:expense_categories,code,' . $expenseCategory->id,
            'description' => 'nullable|string',
            'account_id' => 'nullable|exists:chart_of_accounts,id',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $expenseCategory->update($validated);
        return redirect()->route('expense-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        if ($expenseCategory->expenses()->count() > 0) {
            return redirect()->route('expense-categories.index')->with('error', 'Cannot delete category with existing expenses.');
        }

        $expenseCategory->delete();
        return redirect()->route('expense-categories.index')->with('success', 'Category deleted successfully.');
    }
}
