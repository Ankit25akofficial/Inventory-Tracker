<x-app-layout>
    <x-slot name="title">Edit Supplier</x-slot>

    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Supplier</h1>
            <div class="page-subtitle">{{ $supplier->name }}</div>
        </div>
        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card" style="max-width:800px;">
        <div class="card-body" style="padding:24px;">
            <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
                @csrf
                @method('PUT')
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                    <div class="form-group">
                        <label class="form-label">Contact Name <span style="color:#f87171">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $supplier->name) }}" required>
                        @error('name')<div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $supplier->company_name) }}">
                        @error('company_name')<div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $supplier->email) }}">
                        @error('email')<div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $supplier->phone) }}">
                        @error('phone')<div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group" style="margin-top:20px;">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="3">{{ old('address', $supplier->address) }}</textarea>
                    @error('address')<div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <div style="margin-top:24px;border-top:1px solid #1e2536;padding-top:20px;">
                    <button type="submit" class="btn btn-primary">Update Supplier</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
