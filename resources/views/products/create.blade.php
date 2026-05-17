<x-app-layout>
    <x-slot name="title">Add Product</x-slot>

    <div class="page-header">
        <div>
            <h1 class="page-title">Add Product</h1>
            <div class="page-subtitle">Register a new product to inventory</div>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Basic Information</div>
                </div>
                <div class="card-body" style="padding:24px;">
                    <div class="form-group">
                        <label class="form-label">Product Name <span style="color:#f87171">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')<div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                        <div class="form-group">
                            <label class="form-label">SKU (Barcode/QR Data) <span style="color:#f87171">*</span></label>
                            <input type="text" name="sku" class="form-control" value="{{ old('sku') }}" required placeholder="e.g. PRD-12345">
                            @error('sku')<div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Category <span style="color:#f87171">*</span></label>
                            <select name="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
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
                                <input type="number" step="0.01" name="purchase_price" class="form-control" value="{{ old('purchase_price') }}" required>
                                @error('purchase_price')<div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Selling Price ($) <span style="color:#f87171">*</span></label>
                                <input type="number" step="0.01" name="selling_price" class="form-control" value="{{ old('selling_price') }}" required>
                                @error('selling_price')<div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                            <div class="form-group">
                                <label class="form-label">Initial Quantity <span style="color:#f87171">*</span></label>
                                <input type="number" name="quantity" class="form-control" value="{{ old('quantity', 0) }}" required>
                                @error('quantity')<div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Min Stock Level <span style="color:#f87171">*</span></label>
                                <input type="number" name="min_stock_level" class="form-control" value="{{ old('min_stock_level', 5) }}" required>
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
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            @error('supplier_id')<div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Shelf Location</label>
                            <input type="text" name="shelf_location" class="form-control" value="{{ old('shelf_location') }}" placeholder="e.g. A1-Bin4">
                            @error('shelf_location')<div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Product Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            @error('image')<div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:12px;font-size:15px;">
                    <i class="fas fa-save"></i> Save Product & Generate QR
                </button>
            </div>
        </div>
    </form>
</x-app-layout>
