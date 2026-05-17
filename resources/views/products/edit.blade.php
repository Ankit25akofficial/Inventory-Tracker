<x-app-layout>
    <x-slot name="title">Edit Product</x-slot>

    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Product</h1>
            <div class="page-subtitle">{{ $product->name }} ({{ $product->sku }})</div>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Basic Information</div>
                </div>
                <div class="card-body" style="padding:24px;">
                    <div class="form-group">
                        <label class="form-label">Product Name <span style="color:#f87171">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                        @error('name')<div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                        <div class="form-group">
                            <label class="form-label">SKU (Barcode/QR Data) <span style="color:#f87171">*</span></label>
                            <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}" required>
                            @error('sku')<div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Category <span style="color:#f87171">*</span></label>
                            <select name="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
                        @error('description')<div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div style="display:flex;flex-direction:column;gap:24px;">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Pricing & Stock</div>
                    </div>
                    <div class="card-body" style="padding:24px;">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                            <div class="form-group">
                                <label class="form-label">Purchase Price ($) <span style="color:#f87171">*</span></label>
                                <input type="number" step="0.01" name="purchase_price" class="form-control" value="{{ old('purchase_price', $product->purchase_price) }}" required>
                                @error('purchase_price')<div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Selling Price ($) <span style="color:#f87171">*</span></label>
                                <input type="number" step="0.01" name="selling_price" class="form-control" value="{{ old('selling_price', $product->selling_price) }}" required>
                                @error('selling_price')<div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                            <div class="form-group">
                                <label class="form-label">Quantity <span style="color:#f87171">*</span></label>
                                <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $product->quantity) }}" required>
                                @error('quantity')<div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Min Stock Level <span style="color:#f87171">*</span></label>
                                <input type="number" name="min_stock_level" class="form-control" value="{{ old('min_stock_level', $product->min_stock_level) }}" required>
                                @error('min_stock_level')<div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Additional Info</div>
                    </div>
                    <div class="card-body" style="padding:24px;">
                        <div class="form-group">
                            <label class="form-label">Supplier <span style="color:#f87171">*</span></label>
                            <select name="supplier_id" class="form-control" required>
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            @error('supplier_id')<div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Shelf Location</label>
                            <input type="text" name="shelf_location" class="form-control" value="{{ old('shelf_location', $product->shelf_location) }}">
                            @error('shelf_location')<div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Product Image</label>
                            @if($product->image_path)
                                <div style="margin-bottom:12px;">
                                    <img src="{{ Storage::url($product->image_path) }}" alt="Current Image" style="width:100px;height:100px;object-fit:cover;border-radius:8px;border:1px solid #1e2536;">
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control" accept="image/*">
                            @error('image')<div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:12px;font-size:15px;">
                    <i class="fas fa-save"></i> Update Product
                </button>
            </div>
        </div>
    </form>
</x-app-layout>
