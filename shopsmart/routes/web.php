<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\QuotationReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChartOfAccountController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\CapitalController;
use App\Http\Controllers\LiabilityController;
use App\Http\Controllers\BankReconciliationController;
use App\Http\Controllers\DeliveryNoteController;
use App\Http\Controllers\FinancialStatementController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CustomReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Auth;

// Public Routes (No Authentication Required)
Route::get('/landing', [LandingController::class, 'index'])->name('landing');
Route::get('/shop', [LandingController::class, 'index'])->name('shop');
Route::get('/products', function() { return view('products'); })->name('products');
Route::get('/services', function() { return view('services'); })->name('services');
Route::get('/about', function() { return view('about'); })->name('about');
Route::get('/contact', function() { return view('contact'); })->name('contact');
Route::get('/search-products', [LandingController::class, 'search'])->name('landing.search');
Route::get('/category/{id}/products', [LandingController::class, 'getCategoryProducts'])->name('landing.category.products');
Route::get('/product/{id}/details', [LandingController::class, 'getProductDetails'])->name('landing.product.details');
Route::post('/cart/add', [LandingController::class, 'addToCart'])->name('landing.cart.add');
Route::post('/payment/process', [LandingController::class, 'processPayment'])->name('landing.payment.process');

// Authentication Routes (Public)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.forgot');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

// Redirect root to landing page
Route::get('/', function () {
    return redirect()->route('landing');
});

// Protected Routes (Require Authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/advanced', [DashboardController::class, 'advanced'])->name('dashboard.advanced');
    Route::get('/dashboard/professional', [DashboardController::class, 'professional'])->name('dashboard.professional');
    
    // Global Search
    Route::get('/search', [SearchController::class, 'search'])->name('search');

    // Category custom routes (must come before resource route)
    Route::get('categories/bulk-organize', [CategoryController::class, 'bulkOrganizePage'])->name('categories.bulk-organize-page');
    Route::post('categories/bulk-organize', [CategoryController::class, 'bulkOrganize'])->name('categories.bulk-organize');
    Route::get('categories/hierarchy', [CategoryController::class, 'hierarchyPage'])->name('categories.hierarchy-page');
    Route::get('categories/manage-hierarchy', [CategoryController::class, 'manageHierarchy'])->name('categories.manage-hierarchy');
    Route::post('categories/update-hierarchy', [CategoryController::class, 'updateHierarchy'])->name('categories.update-hierarchy');
    Route::get('categories/import', [CategoryController::class, 'importPage'])->name('categories.import-page');
    Route::post('categories/import', [CategoryController::class, 'importCategories'])->name('categories.import');
    Route::get('categories/export', [CategoryController::class, 'exportCategories'])->name('categories.export');
    
    Route::resource('categories', CategoryController::class);
    Route::resource('stock-movements', StockMovementController::class);
    Route::get('/inventory/low-stock', [ProductController::class, 'lowStock'])->name('inventory.low-stock');

    // Sales - Specific routes must come before resource routes
    Route::get('/sales/invoices', [SaleController::class, 'invoices'])->name('sales.invoices');
    Route::get('/sales/returns', [SaleController::class, 'returns'])->name('sales.returns');
    Route::get('/sales/{sale}/print', [SaleController::class, 'print'])->name('sales.print');
    Route::get('/sales/{sale}/pdf', [SaleController::class, 'pdf'])->name('sales.pdf');
    Route::post('/sales/{sale}/record-payment', [SaleController::class, 'recordPayment'])->name('sales.record-payment');
    Route::resource('sales', SaleController::class);
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos/complete', [POSController::class, 'complete'])->name('pos.complete');
});

// Product custom routes (must come before resource route) - TEMPORARILY OUTSIDE AUTH FOR TESTING
Route::get('products/bulk-operations', [ProductController::class, 'bulkOperationsPage'])->name('products.bulk-operations-page');
Route::post('products/bulk-operations', [ProductController::class, 'bulkOperations'])->name('products.bulk-operations');
Route::get('products/stock-movements', [ProductController::class, 'stockMovementsPage'])->name('products.stock-movements-page');
Route::get('products/low-stock', [ProductController::class, 'lowStockPage'])->name('products.low-stock-page');
Route::get('products/import', [ProductController::class, 'importPage'])->name('products.import-page');
Route::post('products/import', [ProductController::class, 'importProducts'])->name('products.import');
Route::get('products/sample-excel', [ProductController::class, 'downloadSampleExcel'])->name('products.sample-excel');
Route::get('products/export', [ProductController::class, 'exportProducts'])->name('products.export');

// Warehouse custom routes (must come before resource route) - TEMPORARILY OUTSIDE AUTH FOR TESTING
Route::get('warehouses', [WarehouseController::class, 'index'])->name('warehouses.index');
Route::get('warehouses/transfer-stock', [WarehouseController::class, 'transferStockPage'])->name('warehouses.transfer-stock');
Route::post('warehouses/transfer-stock', [WarehouseController::class, 'transferStock'])->name('warehouses.transfer-stock.post');
Route::get('warehouses/capacity-report', [WarehouseController::class, 'capacityReportPage'])->name('warehouses.capacity-report');
Route::get('warehouses/manage-locations', [WarehouseController::class, 'manageLocationsPage'])->name('warehouses.manage-locations');
Route::post('warehouses/manage-locations', [WarehouseController::class, 'manageLocations'])->name('warehouses.manage-locations.post');
Route::get('warehouses/create', [WarehouseController::class, 'create'])->name('warehouses.create');
Route::post('warehouses', [WarehouseController::class, 'store'])->name('warehouses.store');
Route::get('warehouses/{warehouse}', [WarehouseController::class, 'show'])->name('warehouses.show');
Route::get('warehouses/{warehouse}/edit', [WarehouseController::class, 'edit'])->name('warehouses.edit');
Route::put('warehouses/{warehouse}', [WarehouseController::class, 'update'])->name('warehouses.update');
Route::delete('warehouses/{warehouse}', [WarehouseController::class, 'destroy'])->name('warehouses.destroy');

// Stock Movements routes
Route::get('stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');
Route::post('stock-movements/add', [StockMovementController::class, 'addMovement'])->name('stock-movements.add');
Route::get('stock-movements/export', [StockMovementController::class, 'export'])->name('stock-movements.export');

// Inventory routes
Route::get('inventory/low-stock', [ProductController::class, 'lowStock'])->name('inventory.low-stock');
Route::get('products/low-stock', [ProductController::class, 'lowStockPage'])->name('products.low-stock');

Route::resource('products', ProductController::class);    
    // API Routes for POS
    Route::get('/api/sales/today', function() {
        $total = \App\Models\Sale::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total');
        return response()->json(['total' => $total]);
    });
    
    Route::get('/api/products', function() {
        $products = \App\Models\Product::where('is_active', true)
            ->with(['category', 'warehouse'])
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'barcode' => $product->barcode,
                    'category_id' => $product->category_id,
                    'selling_price' => (float) $product->selling_price,
                    'stock_quantity' => $product->stock_quantity,
                    'low_stock_alert' => $product->low_stock_alert,
                    'track_stock' => $product->track_stock,
                    'image' => $product->image,
                    'unit' => $product->unit,
                ];
            });
        return response()->json($products);
    });

    // Quotations - Specific routes must come before resource routes
    Route::get('/quotations/reports', [QuotationReportController::class, 'overview'])->name('quotations.reports');
    Route::get('/quotations/reports/overview', [QuotationReportController::class, 'overview'])->name('quotations.reports.overview');
    Route::get('/quotations/{quotation}/pdf', [QuotationController::class, 'downloadPDF'])->name('quotations.pdf');
    Route::post('/quotations/{quotation}/update-status', [QuotationController::class, 'updateStatus'])->name('quotations.update-status');
    Route::post('/quotations/{quotation}/convert-to-sale', [QuotationController::class, 'convertToSale'])->name('quotations.convert-to-sale');
    Route::post('/quotations/{quotation}/send-email', [QuotationController::class, 'sendEmail'])->name('quotations.send-email');
    Route::resource('quotations', QuotationController::class);

    // Purchases
    Route::get('/purchases/orders', [PurchaseController::class, 'orders'])->name('purchases.orders');
    Route::resource('purchases', PurchaseController::class);
    Route::resource('suppliers', SupplierController::class);

    // Customers
    Route::get('/customers/loyalty', [CustomerController::class, 'loyalty'])->name('customers.loyalty');
    Route::resource('customers', CustomerController::class);

    // Employees
    Route::get('/employees/roles', [EmployeeController::class, 'roles'])->name('employees.roles');
    Route::get('/employees/attendance', [EmployeeController::class, 'attendance'])->name('employees.attendance');
    Route::resource('employees', EmployeeController::class);

    // Financial
    Route::get('/financial', [FinancialController::class, 'index'])->name('financial.index');
Route::get('/financial/income', [FinancialController::class, 'income'])->name('financial.income');
    Route::get('/financial/profit-loss', [FinancialController::class, 'profitLoss'])->name('financial.profit-loss');
    
    // Expenses - PDF route must come BEFORE resource route
    Route::get('/expenses/pdf', [ExpenseController::class, 'pdf'])->name('expenses.pdf');
    Route::resource('expenses', ExpenseController::class);
    Route::resource('transactions', TransactionController::class);

    // Chart of Accounts - PDF route must come BEFORE resource route
    Route::get('/chart-of-accounts/pdf', [ChartOfAccountController::class, 'pdf'])->name('chart-of-accounts.pdf');
    Route::resource('chart-of-accounts', ChartOfAccountController::class);

    // Expense Categories - PDF route must come BEFORE resource route
    Route::get('/expense-categories/pdf', [ExpenseCategoryController::class, 'pdf'])->name('expense-categories.pdf');
    Route::resource('expense-categories', ExpenseCategoryController::class);

    // Capital - PDF route must come BEFORE resource route
    Route::get('/capital/pdf', [CapitalController::class, 'pdf'])->name('capital.pdf');
    Route::resource('capital', CapitalController::class);

    // Liabilities - PDF route must come BEFORE resource route
    Route::get('/liabilities/pdf', [LiabilityController::class, 'pdf'])->name('liabilities.pdf');
    Route::resource('liabilities', LiabilityController::class);

    // Bank Reconciliation - PDF route must come BEFORE resource route
    Route::get('/bank-reconciliations/pdf', [BankReconciliationController::class, 'pdf'])->name('bank-reconciliations.pdf');
    Route::resource('bank-reconciliations', BankReconciliationController::class);

    // Delivery Notes - PDF routes must come BEFORE resource route
    Route::get('/delivery-notes/pdf/list', [DeliveryNoteController::class, 'pdfList'])->name('delivery-notes.pdf.list');
    Route::get('/delivery-notes/{deliveryNote}/pdf', [DeliveryNoteController::class, 'pdf'])->name('delivery-notes.pdf');
    Route::resource('delivery-notes', DeliveryNoteController::class);

    // Financial Statements
    Route::get('/financial-statements/profit-loss', [FinancialStatementController::class, 'profitLoss'])->name('financial-statements.profit-loss');
    Route::get('/financial-statements/profit-loss/pdf', [FinancialStatementController::class, 'profitLossPdf'])->name('financial-statements.profit-loss.pdf');
    Route::get('/financial-statements/balance-sheet', [FinancialStatementController::class, 'balanceSheet'])->name('financial-statements.balance-sheet');
    Route::get('/financial-statements/balance-sheet/pdf', [FinancialStatementController::class, 'balanceSheetPdf'])->name('financial-statements.balance-sheet.pdf');
    Route::get('/financial-statements/trial-balance', [FinancialStatementController::class, 'trialBalance'])->name('financial-statements.trial-balance');
    Route::get('/financial-statements/trial-balance/pdf', [FinancialStatementController::class, 'trialBalancePdf'])->name('financial-statements.trial-balance.pdf');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/sales/pdf', [ReportController::class, 'salesPdf'])->name('reports.sales.pdf');
    Route::get('/reports/purchases', [ReportController::class, 'purchases'])->name('reports.purchases');
    Route::get('/reports/purchases/pdf', [ReportController::class, 'purchasesPdf'])->name('reports.purchases.pdf');
    Route::get('/reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
    Route::get('/reports/inventory/pdf', [ReportController::class, 'inventoryPdf'])->name('reports.inventory.pdf');
    Route::get('/reports/financial', [ReportController::class, 'sales'])->name('reports.financial');
    Route::get('/reports/customers', [ReportController::class, 'customers'])->name('reports.customers');
    Route::get('/reports/customers/{customer}/statement', [ReportController::class, 'customerStatement'])->name('reports.customer-statement');
    Route::get('/reports/customers/{customer}/statement/pdf', [ReportController::class, 'customerStatementPdf'])->name('reports.customer-statement.pdf');
    Route::get('/reports/suppliers', [ReportController::class, 'suppliers'])->name('reports.suppliers');
    Route::get('/reports/suppliers/{supplier}/statement', [ReportController::class, 'supplierStatement'])->name('reports.supplier-statement');
    Route::get('/reports/suppliers/{supplier}/statement/pdf', [ReportController::class, 'supplierStatementPdf'])->name('reports.supplier-statement.pdf');
    Route::get('/reports/profit-loss', [FinancialStatementController::class, 'profitLoss'])->name('reports.profit-loss');

    // Custom Report Builder
    Route::get('/reports/custom-builder', [CustomReportController::class, 'index'])->name('reports.custom-builder');
    Route::post('/reports/custom-builder/generate', [CustomReportController::class, 'generate'])->name('reports.custom-builder.generate');

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->name('index');
    Route::get('/general', [SettingsController::class, 'general'])->name('general');
    Route::post('/general', [SettingsController::class, 'updateGeneral'])->name('general.update');
    Route::get('/users', [SettingsController::class, 'users'])->name('users');
        Route::get('/users/create', [SettingsController::class, 'createUser'])->name('users.create');
        Route::post('/users', [SettingsController::class, 'storeUser'])->name('users.store');
        Route::put('/users/{user}', [SettingsController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [SettingsController::class, 'deleteUser'])->name('users.delete');
    Route::get('/activity-log', [SettingsController::class, 'activityLog'])->name('activity-log');
    Route::get('/roles', [SettingsController::class, 'roles'])->name('roles');
    Route::get('/system', [SettingsController::class, 'system'])->name('system');
    Route::post('/system', [SettingsController::class, 'updateSystem'])->name('system.update');
    Route::get('/financial', [SettingsController::class, 'financial'])->name('financial');
    Route::post('/financial', [SettingsController::class, 'updateFinancial'])->name('financial.update');
    Route::get('/inventory', [SettingsController::class, 'inventory'])->name('inventory');
    Route::post('/inventory', [SettingsController::class, 'updateInventory'])->name('inventory.update');
    Route::get('/quotations', [SettingsController::class, 'quotations'])->name('quotations');
    Route::post('/quotations', [SettingsController::class, 'updateQuotations'])->name('quotations.update');
    Route::get('/notifications', [SettingsController::class, 'notifications'])->name('notifications');
    Route::post('/notifications', [SettingsController::class, 'updateNotifications'])->name('notifications.update');
    
    // Communication Configurations
    Route::prefix('communication')->name('communication.')->group(function () {
        Route::get('/', [SettingsController::class, 'communicationIndex'])->name('index');
        Route::get('/email/create', [SettingsController::class, 'emailCreate'])->name('email.create');
        Route::get('/email/edit/{id}', [SettingsController::class, 'emailEdit'])->name('email.edit');
        Route::put('/email/update/{id}', [SettingsController::class, 'emailUpdate'])->name('email.update');
        Route::get('/sms/create', [SettingsController::class, 'smsCreate'])->name('sms.create');
        Route::get('/sms/edit/{id}', [SettingsController::class, 'smsEdit'])->name('sms.edit');
        Route::put('/sms/update/{id}', [SettingsController::class, 'smsUpdate'])->name('sms.update');
        Route::post('/sms/store', [SettingsController::class, 'smsStore'])->name('sms.store');
        Route::get('/sms/provider', [SettingsController::class, 'smsProvider'])->name('sms.provider');
        Route::post('/test-sms', [SettingsController::class, 'testSMS'])->name('test-sms');
        Route::get('/balance', [SettingsController::class, 'getSmsBalance'])->name('balance');
        Route::get('/logs', [SettingsController::class, 'getSmsLogs'])->name('logs');
        Route::delete('/{id}', [SettingsController::class, 'destroy'])->name('destroy');
        Route::post('/email/store', [SettingsController::class, 'emailStore'])->name('email.store');
        Route::post('/email/test', [SettingsController::class, 'testEmail'])->name('email.test');
    });
    
    // Test endpoints
    
    Route::get('/backup', [SettingsController::class, 'backup'])->name('backup');
    Route::post('/backup/create', [SettingsController::class, 'createBackup'])->name('backup.create');
    Route::post('/backup/schedule', [SettingsController::class, 'scheduleBackup'])->name('backup.schedule');
    Route::post('/backup/automation', [SettingsController::class, 'updateAutomation'])->name('backup.automation');
    Route::post('/backup/clear-cache', [SettingsController::class, 'clearCache'])->name('backup.clear-cache');
    Route::post('/backup/clear-views', [SettingsController::class, 'clearViews'])->name('backup.clear-views');
    Route::post('/backup/clear-routes', [SettingsController::class, 'clearRoutes'])->name('backup.clear-routes');
    Route::post('/backup/clear-config', [SettingsController::class, 'clearConfig'])->name('backup.clear-config');
    Route::post('/backup/optimize-db', [SettingsController::class, 'optimizeDb'])->name('backup.optimize-db');
    Route::post('/backup/clear-all', [SettingsController::class, 'clearAll'])->name('backup.clear-all');
        Route::resource('user-roles', UserRoleController::class);
    });

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::match(['put', 'post'], '/update', [ProfileController::class, 'update'])->name('update');
        Route::match(['put', 'post'], '/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        Route::match(['put', 'post'], '/preferences', [ProfileController::class, 'updatePreferences'])->name('preferences.update');
        Route::get('/activity', [ProfileController::class, 'activity'])->name('activity');
        Route::get('/security', [ProfileController::class, 'security'])->name('security');
    });

    // SMS Logs Routes
    Route::prefix('admin/sms')->name('admin.sms.')->group(function () {
        Route::get('/logs', [App\Http\Controllers\Admin\SmsLogsController::class, 'index'])->name('logs.index');
        Route::post('/logs/sync', [App\Http\Controllers\Admin\SmsLogsController::class, 'syncFromApi'])->name('logs.sync');
        Route::get('/logs/{smsLog}', [App\Http\Controllers\Admin\SmsLogsController::class, 'show'])->name('logs.show');
        Route::get('/logs/export/pdf', [App\Http\Controllers\Admin\SmsLogsController::class, 'exportPdf'])->name('logs.export.pdf');
        Route::get('/logs/export/excel', [App\Http\Controllers\Admin\SmsLogsController::class, 'exportExcel'])->name('logs.export.excel');
    });

    // API Routes for Messaging Service
    Route::prefix('api/sms')->name('api.sms.')->group(function () {
        Route::get('/balance', [App\Http\Controllers\Api\SmsController::class, 'balance'])->name('balance');
        Route::get('/logs', [App\Http\Controllers\Api\SmsController::class, 'logs'])->name('logs');
        Route::post('/send', [App\Http\Controllers\Api\SmsController::class, 'send'])->name('send');
        Route::post('/send-multiple', [App\Http\Controllers\Api\SmsController::class, 'sendMultiple'])->name('sendMultiple');
        Route::post('/schedule', [App\Http\Controllers\Api\SmsController::class, 'schedule'])->name('schedule');
        Route::get('/delivery-reports', [App\Http\Controllers\Api\SmsController::class, 'deliveryReports'])->name('deliveryReports');
        Route::get('/test-connection', [App\Http\Controllers\Api\SmsController::class, 'testConnection'])->name('testConnection');
    });
