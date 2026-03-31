<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent', 'children')->latest()->paginate(20);
        
        // Calculate statistics
        $activeCategories = Category::where('is_active', true)->count();
        $totalProducts = \App\Models\Product::count();
        $subcategoriesCount = Category::whereNotNull('parent_id')->count();
        
        return view('categories.index', compact('categories', 'activeCategories', 'totalProducts', 'subcategoriesCount'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Generate slug from name if not provided
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);

        Category::create($validated);
        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        $category->load(['products', 'parent', 'children']);
        return view('categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Generate slug from name if name changed
        if ($category->name !== $validated['name']) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        }

        $category->update($validated);
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }

    public function bulkOrganize(Request $request)
    {
        $action = $request->input('action');
        $categoryIds = $request->input('categories', []);

        if (empty($categoryIds)) {
            return response()->json(['error' => 'No categories selected'], 400);
        }

        switch ($action) {
            case 'activate':
                Category::whereIn('id', $categoryIds)->update(['is_active' => true]);
                $message = 'Categories activated successfully.';
                break;
            case 'deactivate':
                Category::whereIn('id', $categoryIds)->update(['is_active' => false]);
                $message = 'Categories deactivated successfully.';
                break;
            case 'delete':
                Category::whereIn('id', $categoryIds)->delete();
                $message = 'Categories deleted successfully.';
                break;
            default:
                return response()->json(['error' => 'Invalid action'], 400);
        }

        return response()->json(['success' => true, 'message' => $message]);
    }

    public function manageHierarchy(Request $request)
    {
        $categories = Category::with('parent', 'children')->get();
        return response()->json(['categories' => $categories]);
    }

    public function updateHierarchy(Request $request)
    {
        $hierarchy = $request->input('hierarchy', []);
        
        foreach ($hierarchy as $item) {
            $category = Category::find($item['id']);
            if ($category) {
                $category->parent_id = $item['parent_id'] ?? null;
                $category->save();
            }
        }

        return response()->json(['success' => true, 'message' => 'Category hierarchy updated successfully.']);
    }

    public function importCategories(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:10240'
        ]);

        $file = $request->file('file');
        $imported = 0;
        $errors = [];

        try {
            if ($file->getClientOriginalExtension() === 'csv') {
                $handle = fopen($file->getPathname(), 'r');
                $header = fgetcsv($handle);
                
                while (($row = fgetcsv($handle)) !== false) {
                    try {
                        Category::create([
                            'name' => $row[0] ?? '',
                            'slug' => \Illuminate\Support\Str::slug($row[0] ?? ''),
                            'description' => $row[1] ?? '',
                            'is_active' => filter_var($row[2] ?? true, FILTER_VALIDATE_BOOLEAN),
                        ]);
                        $imported++;
                    } catch (\Exception $e) {
                        $errors[] = "Error importing row: " . $e->getMessage();
                    }
                }
                fclose($handle);
            }
            
            return response()->json([
                'success' => true, 
                'message' => "Successfully imported {$imported} categories.",
                'imported' => $imported,
                'errors' => $errors
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Import failed: ' . $e->getMessage()], 500);
        }
    }

    public function exportCategories()
    {
        $categories = Category::all();
        
        $filename = "categories_" . date('Y-m-d') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($categories) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Slug', 'Description', 'Status', 'Created At']);
            
            foreach ($categories as $category) {
                fputcsv($file, [
                    $category->name,
                    $category->slug,
                    $category->description,
                    $category->is_active ? 'Active' : 'Inactive',
                    $category->created_at->format('Y-m-d H:i:s')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function bulkOrganizePage()
    {
        $categories = Category::with('parent', 'children')->get();
        return view('categories.bulk-organize', compact('categories'));
    }

    public function hierarchyPage()
    {
        $categories = Category::with('parent', 'children')->get();
        return view('categories.hierarchy', compact('categories'));
    }

    public function importPage()
    {
        return view('categories.import');
    }
}
