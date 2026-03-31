@extends('layouts.app')

@section('title', 'Import Products')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Import Products</h1>
            <p class="text-gray-600 mt-1">Import products from CSV or Excel files</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('products.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back to Products</span>
            </a>
        </div>
    </div>

    <!-- Import Instructions -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h2 class="text-lg font-semibold text-blue-900 mb-4">Import Instructions</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-medium text-blue-800 mb-3">📋 CSV Format Requirements</h3>
                <div class="bg-white rounded-lg p-4 border border-blue-200">
                    <p class="text-sm text-gray-700 mb-2">Your CSV file should have the following columns:</p>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center space-x-2">
                            <span class="font-mono bg-gray-100 px-2 py-1 rounded">Column A</span>
                            <span class="text-gray-600">Name (required)</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="font-mono bg-gray-100 px-2 py-1 rounded">Column B</span>
                            <span class="text-gray-600">SKU (optional)</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="font-mono bg-gray-100 px-2 py-1 rounded">Column C</span>
                            <span class="text-gray-600">Selling Price (required)</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="font-mono bg-gray-100 px-2 py-1 rounded">Column D</span>
                            <span class="text-gray-600">Stock Quantity (optional)</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="font-mono bg-gray-100 px-2 py-1 rounded">Column E</span>
                            <span class="text-gray-600">Category ID (optional)</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="font-mono bg-gray-100 px-2 py-1 rounded">Column F</span>
                            <span class="text-gray-600">Status (true/false, optional)</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="font-medium text-blue-800 mb-3">📝 Example CSV Content</h3>
                <div class="bg-white rounded-lg p-4 border border-blue-200">
                    <pre class="text-xs text-gray-700 overflow-x-auto">Laptop Pro 15,LP-001,1500000,25,1,true
Wireless Mouse,WM-002,45000,100,1,true
USB Cable,UC-003,5000,200,2,true
Monitor 24",MN-004,850000,15,1,false</pre>
                </div>
            </div>
        </div>
        
        <div class="mt-4 p-3 bg-blue-100 rounded-lg">
            <p class="text-sm text-blue-700">
                <strong>Important:</strong> Make sure your file is UTF-8 encoded and doesn't contain special characters in headers. Maximum file size is 10MB.
            </p>
        </div>
    </div>

    <!-- Import Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Upload File</h3>
        
        <form id="importForm" enctype="multipart/form-data" class="space-y-6">
            <div>
                <label for="importFile" class="block text-sm font-medium text-gray-700 mb-2">Select File</label>
                <div class="relative">
                    <input type="file" 
                           id="importFile" 
                           name="file" 
                           accept=".csv,.xlsx,.xls"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <div class="mt-2 text-sm text-gray-500">
                        Accepted formats: CSV, Excel (.xlsx, .xls) - Max file size: 10MB
                    </div>
                </div>
            </div>

            <!-- File Preview -->
            <div id="filePreview" class="hidden">
                <h4 class="font-medium text-gray-900 mb-3">File Preview</h4>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700" id="fileName"></span>
                        <span class="text-sm text-gray-500" id="fileSize"></span>
                    </div>
                    <div id="fileContent" class="text-xs text-gray-600 font-mono bg-white p-3 rounded border border-gray-200 max-h-40 overflow-y-auto"></div>
                </div>
            </div>

            <!-- Import Options -->
            <div class="border-t border-gray-200 pt-6">
                <h4 class="font-medium text-gray-900 mb-3">Import Options</h4>
                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="checkbox" name="skip_duplicates" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" checked>
                        <span class="ml-2 text-sm text-gray-700">Skip duplicate products (by SKU)</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="update_existing" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Update existing products</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="validate_data" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" checked>
                        <span class="ml-2 text-sm text-gray-700">Validate data before import</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="create_categories" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Auto-create missing categories</span>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <div class="text-sm text-gray-500">
                    <span id="recordCount">0</span> records will be imported
                </div>
                <div class="space-x-3">
                    <button type="button" onclick="resetForm()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Reset
                    </button>
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <span>Import Products</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Import Results -->
    <div id="importResults" class="hidden">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Import Results</h3>
        <div id="resultsContent"></div>
    </div>

    <!-- Sample Download -->
    <div class="bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Need a Sample File?</h3>
        <p class="text-gray-600 mb-4">Download sample files to see the expected format:</p>
        <div class="flex flex-wrap gap-3">
            <button onclick="downloadSample('csv')" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Download Sample CSV</span>
            </button>
            <button onclick="downloadSample('excel')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Download Sample Excel</span>
            </button>
        </div>
    </div>
</div>

<script>
let fileData = [];

// File input change handler
document.getElementById('importFile').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        displayFilePreview(file);
        parseFile(file);
    }
});

// Form submit handler
document.getElementById('importForm').addEventListener('submit', function(e) {
    e.preventDefault();
    performImport();
});

function displayFilePreview(file) {
    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileSize').textContent = formatFileSize(file.size);
    document.getElementById('filePreview').classList.remove('hidden');
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function parseFile(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const text = e.target.result;
        const lines = text.split('\n').filter(line => line.trim());
        
        // Show first few lines as preview
        const preview = lines.slice(0, 5).join('\n');
        document.getElementById('fileContent').textContent = preview;
        
        // Count records (excluding header)
        const recordCount = lines.length > 0 ? lines.length - 1 : 0;
        document.getElementById('recordCount').textContent = recordCount;
        
        // Store data for validation
        fileData = lines;
    };
    reader.readAsText(file);
}

function performImport() {
    const formData = new FormData();
    const fileInput = document.getElementById('importFile');
    
    if (!fileInput.files[0]) {
        Swal.fire({
            icon: 'warning',
            title: 'No File Selected',
            text: 'Please select a file to import.',
            confirmButtonColor: '#3B82F6'
        });
        return;
    }
    
    formData.append('file', fileInput.files[0]);
    
    // Add options
    const skipDuplicates = document.querySelector('input[name="skip_duplicates"]').checked;
    const updateExisting = document.querySelector('input[name="update_existing"]').checked;
    const validateData = document.querySelector('input[name="validate_data"]').checked;
    const createCategories = document.querySelector('input[name="create_categories"]').checked;
    
    formData.append('skip_duplicates', skipDuplicates);
    formData.append('update_existing', updateExisting);
    formData.append('validate_data', validateData);
    formData.append('create_categories', createCategories);
    
    // Show loading state
    const submitBtn = document.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<span class="inline-flex items-center"><svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Importing...</span>';
    submitBtn.disabled = true;
    
    fetch('{{ url("products/import") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showImportResults(data);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Import Failed!',
                text: data.error || 'Import failed',
                confirmButtonColor: '#EF4444'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'An error occurred during import',
            confirmButtonColor: '#EF4444'
        });
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

function showImportResults(data) {
    const resultsDiv = document.getElementById('importResults');
    const resultsContent = document.getElementById('resultsContent');
    
    let html = `
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <h4 class="text-green-900 font-semibold mb-2">✅ Import Successful!</h4>
            <p class="text-green-800">${data.message}</p>
            <div class="mt-3 text-sm text-green-700">
                <p>Successfully imported: <strong>${data.imported}</strong> products</p>
    `;
    
    if (data.errors && data.errors.length > 0) {
        html += `
            <div class="mt-3">
                <p class="font-medium text-yellow-700">⚠️ Some rows had errors:</p>
                <ul class="list-disc list-inside mt-1 text-yellow-600 max-h-32 overflow-y-auto">
                    ${data.errors.map(error => `<li>${error}</li>`).join('')}
                </ul>
            </div>
        `;
    }
    
    html += `
            </div>
            <div class="mt-4">
                <a href="{{ route('products.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    View Products
                </a>
            </div>
        </div>
    `;
    
    resultsContent.innerHTML = html;
    resultsDiv.classList.remove('hidden');
    
    // Scroll to results
    resultsDiv.scrollIntoView({ behavior: 'smooth' });
}

function resetForm() {
    document.getElementById('importForm').reset();
    document.getElementById('filePreview').classList.add('hidden');
    document.getElementById('importResults').classList.add('hidden');
    document.getElementById('recordCount').textContent = '0';
    fileData = [];
}

function downloadSample(format) {
    if (format === 'csv') {
        const sampleContent = `Laptop Pro 15,LP-001,1500000,25,1,true
Wireless Mouse,WM-002,45000,100,1,true
USB Cable,UC-003,5000,200,2,true
Monitor 24",MN-004,850000,15,1,false`;
        
        const blob = new Blob([sampleContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'sample_products.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    } else {
        // For Excel, we'll redirect to a route that generates the Excel file
        window.location.href = '{{ url("products/sample-excel") }}';
    }
}
</script>
@endsection
