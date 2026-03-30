<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Expense;
use Carbon\Carbon;

class CustomReportController extends Controller
{
    public function index()
    {
        return view('reports.custom-builder');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'reportType' => 'required|in:sales,inventory,financial,customers,suppliers',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'selectedFields' => 'required|array|min:1',
            'filters' => 'nullable|array'
        ]);

        $reportType = $request->input('reportType');
        $startDate = Carbon::parse($request->input('startDate'));
        $endDate = Carbon::parse($request->input('endDate'))->endOfDay();
        $selectedFields = $request->input('selectedFields');
        $filters = $request->input('filters', []);

        $data = $this->getReportData($reportType, $startDate, $endDate, $selectedFields, $filters);

        return response()->json([
            'success' => true,
            'data' => $data,
            'count' => count($data)
        ]);
    }

    private function getReportData($reportType, $startDate, $endDate, $selectedFields, $filters)
    {
        switch ($reportType) {
            case 'sales':
                return $this->getSalesData($startDate, $endDate, $selectedFields, $filters);
            case 'inventory':
                return $this->getInventoryData($selectedFields, $filters);
            case 'financial':
                return $this->getFinancialData($startDate, $endDate, $selectedFields, $filters);
            case 'customers':
                return $this->getCustomersData($startDate, $endDate, $selectedFields, $filters);
            case 'suppliers':
                return $this->getSuppliersData($startDate, $endDate, $selectedFields, $filters);
            default:
                return [];
        }
    }

    private function getSalesData($startDate, $endDate, $selectedFields, $filters)
    {
        $query = Sale::with('customer')
            ->whereBetween('created_at', [$startDate, $endDate]);

        // Apply filters
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $sales = $query->get();

        return $sales->map(function ($sale) use ($selectedFields) {
            $row = [];
            
            foreach ($selectedFields as $field) {
                switch ($field) {
                    case 'id':
                        $row[$field] = $sale->id;
                        break;
                    case 'invoice_number':
                        $row[$field] = $sale->invoice_number;
                        break;
                    case 'customer_name':
                        $row[$field] = $sale->customer ? $sale->customer->name : 'Walk-in Customer';
                        break;
                    case 'created_at':
                        $row[$field] = $sale->created_at->format('Y-m-d H:i:s');
                        break;
                    case 'total':
                        $row[$field] = number_format($sale->total, 2);
                        break;
                    case 'payment_method':
                        $row[$field] = ucfirst(str_replace('_', ' ', $sale->payment_method));
                        break;
                    case 'status':
                        $row[$field] = ucfirst($sale->status);
                        break;
                    default:
                        $row[$field] = $sale->{$field} ?? '';
                }
            }
            
            return $row;
        })->toArray();
    }

    private function getInventoryData($selectedFields, $filters)
    {
        $query = Product::with('category');

        // Apply filters
        if (!empty($filters['stockLevel'])) {
            switch ($filters['stockLevel']) {
                case 'low':
                    $query->whereColumn('stock_quantity', '<=', 'low_stock_alert');
                    break;
                case 'normal':
                    $query->whereColumn('stock_quantity', '>', 'low_stock_alert')
                          ->where('stock_quantity', '<', 100);
                    break;
                case 'overstock':
                    $query->where('stock_quantity', '>=', 100);
                    break;
            }
        }

        $products = $query->get();

        return $products->map(function ($product) use ($selectedFields) {
            $row = [];
            
            foreach ($selectedFields as $field) {
                switch ($field) {
                    case 'id':
                        $row[$field] = $product->id;
                        break;
                    case 'name':
                        $row[$field] = $product->name;
                        break;
                    case 'sku':
                        $row[$field] = $product->sku ?? '';
                        break;
                    case 'stock_quantity':
                        $row[$field] = $product->stock_quantity;
                        break;
                    case 'cost_price':
                        $row[$field] = number_format($product->cost_price, 2);
                        break;
                    case 'selling_price':
                        $row[$field] = number_format($product->selling_price, 2);
                        break;
                    case 'category':
                        $row[$field] = $product->category ? $product->category->name : '';
                        break;
                    default:
                        $row[$field] = $product->{$field} ?? '';
                }
            }
            
            return $row;
        })->toArray();
    }

    private function getFinancialData($startDate, $endDate, $selectedFields, $filters)
    {
        // Get both income (sales) and expenses
        $income = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get(['id', 'total', 'created_at', 'payment_method']);

        $expenses = Expense::whereBetween('created_at', [$startDate, $endDate])
            ->get(['id', 'amount', 'description', 'expense_date', 'payment_method']);

        $data = [];

        // Process income
        foreach ($income as $sale) {
            $row = [];
            foreach ($selectedFields as $field) {
                switch ($field) {
                    case 'id':
                        $row[$field] = 'SALE-' . $sale->id;
                        break;
                    case 'type':
                        $row[$field] = 'Income';
                        break;
                    case 'category':
                        $row[$field] = 'Sales';
                        break;
                    case 'amount':
                        $row[$field] = number_format($sale->total, 2);
                        break;
                    case 'date':
                        $row[$field] = $sale->created_at->format('Y-m-d');
                        break;
                    case 'description':
                        $row[$field] = 'Sale transaction';
                        break;
                    default:
                        $row[$field] = '';
                }
            }
            $data[] = $row;
        }

        // Process expenses
        foreach ($expenses as $expense) {
            $row = [];
            foreach ($selectedFields as $field) {
                switch ($field) {
                    case 'id':
                        $row[$field] = 'EXP-' . $expense->id;
                        break;
                    case 'type':
                        $row[$field] = 'Expense';
                        break;
                    case 'category':
                        $row[$field] = $expense->category;
                        break;
                    case 'amount':
                        $row[$field] = number_format($expense->amount, 2);
                        break;
                    case 'date':
                        $row[$field] = $expense->expense_date;
                        break;
                    case 'description':
                        $row[$field] = $expense->description;
                        break;
                    default:
                        $row[$field] = '';
                }
            }
            $data[] = $row;
        }

        return $data;
    }

    private function getCustomersData($startDate, $endDate, $selectedFields, $filters)
    {
        $query = Customer::with(['sales' => function($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }]);

        // Apply filters
        if (!empty($filters['customerType'])) {
            switch ($filters['customerType']) {
                case 'active':
                    $query->whereHas('sales', function($q) use ($startDate, $endDate) {
                        $q->whereBetween('created_at', [$startDate, $endDate]);
                    });
                    break;
                case 'inactive':
                    $query->whereDoesntHave('sales', function($q) use ($startDate, $endDate) {
                        $q->whereBetween('created_at', [$startDate, $endDate]);
                    });
                    break;
            }
        }

        $customers = $query->get();

        return $customers->map(function ($customer) use ($selectedFields, $startDate, $endDate) {
            $row = [];
            
            foreach ($selectedFields as $field) {
                switch ($field) {
                    case 'id':
                        $row[$field] = $customer->id;
                        break;
                    case 'name':
                        $row[$field] = $customer->name;
                        break;
                    case 'email':
                        $row[$field] = $customer->email ?? '';
                        break;
                    case 'phone':
                        $row[$field] = $customer->phone ?? '';
                        break;
                    case 'total_purchases':
                        $total = $customer->sales()
                            ->whereBetween('created_at', [$startDate, $endDate])
                            ->sum('total');
                        $row[$field] = number_format($total, 2);
                        break;
                    case 'last_order':
                        $lastOrder = $customer->sales()
                            ->whereBetween('created_at', [$startDate, $endDate])
                            ->latest()
                            ->first();
                        $row[$field] = $lastOrder ? $lastOrder->created_at->format('Y-m-d') : '';
                        break;
                    default:
                        $row[$field] = $customer->{$field} ?? '';
                }
            }
            
            return $row;
        })->toArray();
    }

    private function getSuppliersData($startDate, $endDate, $selectedFields, $filters)
    {
        $query = Supplier::with(['purchases' => function($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }]);

        $suppliers = $query->get();

        return $suppliers->map(function ($supplier) use ($selectedFields, $startDate, $endDate) {
            $row = [];
            
            foreach ($selectedFields as $field) {
                switch ($field) {
                    case 'id':
                        $row[$field] = $supplier->id;
                        break;
                    case 'name':
                        $row[$field] = $supplier->name;
                        break;
                    case 'email':
                        $row[$field] = $supplier->email ?? '';
                        break;
                    case 'phone':
                        $row[$field] = $supplier->phone ?? '';
                        break;
                    case 'total_orders':
                        $count = $supplier->purchases()
                            ->whereBetween('created_at', [$startDate, $endDate])
                            ->count();
                        $row[$field] = $count;
                        break;
                    case 'balance':
                        $total = $supplier->purchases()
                            ->whereBetween('created_at', [$startDate, $endDate])
                            ->sum('total');
                        $paid = $supplier->purchases()
                            ->whereBetween('created_at', [$startDate, $endDate])
                            ->sum('paid_amount');
                        $row[$field] = number_format($total - $paid, 2);
                        break;
                    default:
                        $row[$field] = $supplier->{$field} ?? '';
                }
            }
            
            return $row;
        })->toArray();
    }
}
