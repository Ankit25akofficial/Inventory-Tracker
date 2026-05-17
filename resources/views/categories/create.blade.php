<x-app-layout>
    <x-slot name="title">Add Category</x-slot>

    <div class="page-header">
        <div>
            <h1 class="page-title">Add Category</h1>
            <div class="page-subtitle">Create a new product category</div>
        </div>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card" style="max-width:600px;">
        <div class="card-body" style="padding:24px;">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Category Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name')
                        <div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                    @error('description')
                        <div style="color:#f87171;font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Save Category</button>
            </form>
        </div>
    </div>
</x-app-layout>
