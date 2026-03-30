@extends('layouts.app')

@section('title', 'Custom Report Builder')

@section('content')
<div class="space-y-6" x-data="customReportBuilder()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Custom Report Builder</h1>
            <p class="text-gray-600 mt-1">Create personalized reports with custom filters and fields</p>
        </div>
        <div class="flex gap-2">
            <button @click="resetBuilder()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                <i class="fas fa-redo mr-2"></i>Reset
            </button>
            <button @click="previewReport()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-eye mr-2"></i>Preview
            </button>
            <button @click="generateReport()" class="px-4 py-2 text-white rounded-lg hover:bg-green-700 transition-colors" style="background-color: #009245;">
                <i class="fas fa-file-export mr-2"></i>Generate Report
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Report Configuration -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Report Type -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Report Type</h3>
                <div class="space-y-3">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="radio" name="reportType" value="sales" x-model="reportType" class="text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Sales Report</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="radio" name="reportType" value="inventory" x-model="reportType" class="text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Inventory Report</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="radio" name="reportType" value="financial" x-model="reportType" class="text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Financial Report</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="radio" name="reportType" value="customers" x-model="reportType" class="text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Customer Report</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="radio" name="reportType" value="suppliers" x-model="reportType" class="text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Supplier Report</span>
                    </label>
                </div>
            </div>

            <!-- Date Range -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Date Range</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                        <input type="date" x-model="startDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                        <input type="date" x-model="endDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex gap-2">
                        <button @click="setDateRange('today')" class="flex-1 px-3 py-2 text-sm bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors">Today</button>
                        <button @click="setDateRange('week')" class="flex-1 px-3 py-2 text-sm bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors">This Week</button>
                        <button @click="setDateRange('month')" class="flex-1 px-3 py-2 text-sm bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors">This Month</button>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Filters</h3>
                <div class="space-y-4">
                    <div x-show="reportType === 'sales'">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select x-model="filters.status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Status</option>
                            <option value="completed">Completed</option>
                            <option value="pending">Pending</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div x-show="reportType === 'inventory'">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stock Level</label>
                        <select x-model="filters.stockLevel" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Levels</option>
                            <option value="low">Low Stock</option>
                            <option value="normal">Normal</option>
                            <option value="overstock">Overstock</option>
                        </select>
                    </div>
                    <div x-show="reportType === 'customers'">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Customer Type</label>
                        <select x-model="filters.customerType" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Customers</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="vip">VIP</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Fields and Preview -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Fields Selection -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Select Fields</h3>
                <div class="grid grid-cols-2 gap-4">
                    <template x-for="field in availableFields" :key="field.key">
                        <label class="flex items-center space-x-3 cursor-pointer p-3 border rounded-lg hover:bg-gray-50 transition-colors">
                            <input type="checkbox" :value="field.key" x-model="selectedFields" class="text-blue-600 focus:ring-blue-500">
                            <div>
                                <span class="text-sm font-medium text-gray-700" x-text="field.label"></span>
                                <p class="text-xs text-gray-500" x-text="field.description"></p>
                            </div>
                        </label>
                    </template>
                </div>
            </div>

            <!-- Report Preview -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Report Preview</h3>
                <div x-show="!previewData.length" class="text-center py-12 text-gray-500">
                    <i class="fas fa-table text-4xl mb-4"></i>
                    <p>Configure your report and click "Preview" to see the results</p>
                </div>
                <div x-show="previewData.length" class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <template x-for="field in selectedFields" :key="field">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" x-text="getFieldLabel(field)"></th>
                                </template>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="row in previewData" :key="row.id">
                                <tr>
                                    <template x-for="field in selectedFields" :key="field">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="row[field]"></td>
                                    </template>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function customReportBuilder() {
    return {
        reportType: 'sales',
        startDate: '',
        endDate: '',
        selectedFields: ['id', 'created_at', 'total'],
        filters: {
            status: '',
            stockLevel: '',
            customerType: ''
        },
        previewData: [],
        
        availableFields: {
            sales: [
                { key: 'id', label: 'ID', description: 'Transaction ID' },
                { key: 'invoice_number', label: 'Invoice #', description: 'Invoice number' },
                { key: 'customer_name', label: 'Customer', description: 'Customer name' },
                { key: 'created_at', label: 'Date', description: 'Transaction date' },
                { key: 'total', label: 'Total', description: 'Total amount' },
                { key: 'payment_method', label: 'Payment Method', description: 'How payment was made' },
                { key: 'status', label: 'Status', description: 'Transaction status' }
            ],
            inventory: [
                { key: 'id', label: 'ID', description: 'Product ID' },
                { key: 'name', label: 'Product Name', description: 'Product name' },
                { key: 'sku', label: 'SKU', description: 'Product SKU' },
                { key: 'stock_quantity', label: 'Stock', description: 'Current stock level' },
                { key: 'cost_price', label: 'Cost Price', description: 'Purchase cost' },
                { key: 'selling_price', label: 'Selling Price', description: 'Retail price' },
                { key: 'category', label: 'Category', description: 'Product category' }
            ],
            financial: [
                { key: 'id', label: 'ID', description: 'Transaction ID' },
                { key: 'type', label: 'Type', description: 'Income/Expense' },
                { key: 'category', label: 'Category', description: 'Transaction category' },
                { key: 'amount', label: 'Amount', description: 'Transaction amount' },
                { key: 'date', label: 'Date', description: 'Transaction date' },
                { key: 'description', label: 'Description', description: 'Transaction details' }
            ],
            customers: [
                { key: 'id', label: 'ID', description: 'Customer ID' },
                { key: 'name', label: 'Name', description: 'Customer name' },
                { key: 'email', label: 'Email', description: 'Email address' },
                { key: 'phone', label: 'Phone', description: 'Phone number' },
                { key: 'total_purchases', label: 'Total Purchases', description: 'Lifetime purchases' },
                { key: 'last_order', label: 'Last Order', description: 'Most recent order' }
            ],
            suppliers: [
                { key: 'id', label: 'ID', description: 'Supplier ID' },
                { key: 'name', label: 'Name', description: 'Supplier name' },
                { key: 'email', label: 'Email', description: 'Email address' },
                { key: 'phone', label: 'Phone', description: 'Phone number' },
                { key: 'total_orders', label: 'Total Orders', description: 'Total purchase orders' },
                { key: 'balance', label: 'Balance', description: 'Outstanding balance' }
            ]
        },
        
        init() {
            this.setDateRange('month');
        },
        
        get currentFields() {
            return this.availableFields[this.reportType] || [];
        },
        
        getFieldLabel(fieldKey) {
            const field = this.currentFields.find(f => f.key === fieldKey);
            return field ? field.label : fieldKey;
        },
        
        setDateRange(range) {
            const today = new Date();
            const formatDate = (date) => date.toISOString().split('T')[0];
            
            switch(range) {
                case 'today':
                    this.startDate = formatDate(today);
                    this.endDate = formatDate(today);
                    break;
                case 'week':
                    const weekStart = new Date(today);
                    weekStart.setDate(today.getDate() - today.getDay());
                    this.startDate = formatDate(weekStart);
                    this.endDate = formatDate(today);
                    break;
                case 'month':
                    const monthStart = new Date(today.getFullYear(), today.getMonth(), 1);
                    this.startDate = formatDate(monthStart);
                    this.endDate = formatDate(today);
                    break;
            }
        },
        
        resetBuilder() {
            this.reportType = 'sales';
            this.selectedFields = ['id', 'created_at', 'total'];
            this.filters = {
                status: '',
                stockLevel: '',
                customerType: ''
            };
            this.previewData = [];
            this.setDateRange('month');
        },
        
        previewReport() {
            // Simulate API call to get preview data
            this.previewData = this.generateMockData();
        },
        
        generateReport() {
            if (!this.selectedFields.length) {
                alert('Please select at least one field to include in the report.');
                return;
            }
            
            // Simulate report generation
            const reportData = this.generateMockData();
            this.downloadReport(reportData);
        },
        
        generateMockData() {
            const data = [];
            const count = 5; // Show 5 rows in preview
            
            for (let i = 1; i <= count; i++) {
                const row = { id: i };
                
                this.selectedFields.forEach(field => {
                    switch(field) {
                        case 'id':
                            row[field] = i;
                            break;
                        case 'invoice_number':
                            row[field] = `INV-2025-${String(i).padStart(4, '0')}`;
                            break;
                        case 'customer_name':
                            row[field] = `Customer ${i}`;
                            break;
                        case 'created_at':
                        case 'date':
                            row[field] = new Date().toLocaleDateString();
                            break;
                        case 'total':
                        case 'amount':
                        case 'cost_price':
                        case 'selling_price':
                        case 'total_purchases':
                        case 'balance':
                            row[field] = `$${(Math.random() * 1000).toFixed(2)}`;
                            break;
                        case 'payment_method':
                            row[field] = ['Cash', 'Card', 'Mobile Money'][Math.floor(Math.random() * 3)];
                            break;
                        case 'status':
                            row[field] = ['completed', 'pending', 'cancelled'][Math.floor(Math.random() * 3)];
                            break;
                        case 'name':
                            row[field] = `Product ${i}`;
                            break;
                        case 'sku':
                            row[field] = `SKU-${String(i).padStart(3, '0')}`;
                            break;
                        case 'stock_quantity':
                            row[field] = Math.floor(Math.random() * 100);
                            break;
                        case 'category':
                            row[field] = ['Electronics', 'Clothing', 'Food'][Math.floor(Math.random() * 3)];
                            break;
                        case 'type':
                            row[field] = ['Income', 'Expense'][Math.floor(Math.random() * 2)];
                            break;
                        case 'description':
                            row[field] = `Transaction description ${i}`;
                            break;
                        case 'email':
                            row[field] = `user${i}@example.com`;
                            break;
                        case 'phone':
                            row[field] = `+123456789${i}`;
                            break;
                        case 'last_order':
                        case 'total_orders':
                            row[field] = Math.floor(Math.random() * 50);
                            break;
                        default:
                            row[field] = `Data ${i}`;
                    }
                });
                
                data.push(row);
            }
            
            return data;
        },
        
        downloadReport(data) {
            // Convert to CSV
            const headers = this.selectedFields.map(field => this.getFieldLabel(field));
            const csvContent = [
                headers.join(','),
                ...data.map(row => this.selectedFields.map(field => row[field]).join(','))
            ].join('\n');
            
            // Create download
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `custom-report-${this.reportType}-${new Date().toISOString().split('T')[0]}.csv`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
            
            alert('Report generated and downloaded successfully!');
        }
    }
}
</script>
@endsection
