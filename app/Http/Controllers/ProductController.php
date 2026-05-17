<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'supplier'])->latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'shelf_location' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);

        // Generate QR Code
        $qrPath = 'qrcodes/' . $product->sku . '.svg';
        $qrData = route('products.show', $product->id) . '?sku=' . urlencode($product->sku);
        Storage::disk('public')->put($qrPath, QrCode::size(200)->generate($qrData));
        $product->qr_code_path = $qrPath;
        $product->save();

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        // Auto-heal missing QR codes on viewing
        if (empty($product->qr_code_path) || !Storage::disk('public')->exists($product->qr_code_path)) {
            $qrPath = 'qrcodes/' . $product->sku . '.svg';
            $qrData = route('products.show', $product->id) . '?sku=' . urlencode($product->sku);
            Storage::disk('public')->put($qrPath, QrCode::size(200)->generate($qrData));
            $product->qr_code_path = $qrPath;
            $product->save();
        }

        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'shelf_location' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        // Update QR code if SKU changed
        if ($product->wasChanged('sku')) {
            if ($product->qr_code_path) {
                Storage::disk('public')->delete($product->qr_code_path);
            }
            $qrPath = 'qrcodes/' . $product->sku . '.svg';
            $qrData = route('products.show', $product->id) . '?sku=' . urlencode($product->sku);
            Storage::disk('public')->put($qrPath, QrCode::size(200)->generate($qrData));
            $product->qr_code_path = $qrPath;
            $product->save();
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        if ($product->qr_code_path) {
            Storage::disk('public')->delete($product->qr_code_path);
        }
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}

