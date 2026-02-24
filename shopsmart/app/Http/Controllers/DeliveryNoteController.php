<?php

namespace App\Http\Controllers;

use App\Models\DeliveryNote;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DeliveryNoteController extends Controller
{
    public function index(Request $request)
    {
        $query = DeliveryNote::with(['customer', 'supplier', 'sale', 'purchase', 'items']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('delivery_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('delivery_date', '<=', $request->date_to);
        }

        $deliveryNotes = $query->latest()->paginate(20)->appends($request->query());
        return view('delivery-notes.index', compact('deliveryNotes'));
    }

    public function create(Request $request)
    {
        $sales = Sale::where('status', 'completed')->latest()->get();
        $purchases = Purchase::where('status', 'completed')->latest()->get();
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();

        return view('delivery-notes.create', compact('sales', 'purchases', 'customers', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:sale,purchase,transfer',
            'sale_id' => 'nullable|exists:sales,id',
            'purchase_id' => 'nullable|exists:purchases,id',
            'customer_id' => 'nullable|exists:customers,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'delivery_date' => 'required|date',
            'delivery_address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit' => 'nullable|string|max:50',
            'items.*.description' => 'nullable|string',
        ]);

        $validated['delivery_number'] = 'DN-' . strtoupper(uniqid());
        $validated['status'] = 'pending';
        $validated['user_id'] = auth()->id();

        $deliveryNote = DeliveryNote::create($validated);

        // Create items
        foreach ($validated['items'] as $item) {
            $deliveryNote->items()->create($item);
        }

        return redirect()->route('delivery-notes.index')->with('success', 'Delivery note created successfully.');
    }

    public function show(DeliveryNote $deliveryNote)
    {
        $deliveryNote->load(['customer', 'supplier', 'sale', 'purchase', 'items.product', 'user']);
        return view('delivery-notes.show', compact('deliveryNote'));
    }

    public function edit(DeliveryNote $deliveryNote)
    {
        $sales = Sale::where('status', 'completed')->latest()->get();
        $purchases = Purchase::where('status', 'completed')->latest()->get();
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $deliveryNote->load('items');

        return view('delivery-notes.edit', compact('deliveryNote', 'sales', 'purchases', 'customers', 'suppliers'));
    }

    public function update(Request $request, DeliveryNote $deliveryNote)
    {
        $validated = $request->validate([
            'type' => 'required|in:sale,purchase,transfer',
            'sale_id' => 'nullable|exists:sales,id',
            'purchase_id' => 'nullable|exists:purchases,id',
            'customer_id' => 'nullable|exists:customers,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'delivery_date' => 'required|date',
            'delivery_address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'status' => 'required|in:pending,in_transit,delivered,cancelled',
            'notes' => 'nullable|string',
        ]);

        $deliveryNote->update($validated);
        return redirect()->route('delivery-notes.index')->with('success', 'Delivery note updated successfully.');
    }

    public function destroy(DeliveryNote $deliveryNote)
    {
        $deliveryNote->items()->delete();
        $deliveryNote->delete();
        return redirect()->route('delivery-notes.index')->with('success', 'Delivery note deleted successfully.');
    }

    public function pdf(DeliveryNote $deliveryNote)
    {
        $deliveryNote->load(['customer', 'supplier', 'sale', 'purchase', 'items.product', 'user']);
        
        $pdf = Pdf::loadView('delivery-notes.pdf.show', compact('deliveryNote'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('delivery-note-' . $deliveryNote->delivery_number . '.pdf');
    }

    public function pdfList(Request $request)
    {
        $query = DeliveryNote::with(['customer', 'supplier', 'sale', 'purchase', 'items']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('delivery_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('delivery_date', '<=', $request->date_to);
        }

        $deliveryNotes = $query->latest()->get();

        $filters = $request->only(['type', 'status', 'date_from', 'date_to']);

        $pdf = Pdf::loadView('delivery-notes.pdf.index', compact('deliveryNotes', 'filters'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('delivery-notes-report-' . now()->format('Y-m-d') . '.pdf');
    }
}
