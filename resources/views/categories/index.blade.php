<x-app-layout>
    <x-slot name="title">Categories</x-slot>

    <div class="page-header">
        <div>
            <h1 class="page-title">Categories</h1>
            <div class="page-subtitle">Manage product categories</div>
        </div>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Category
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($categories->isEmpty())
                <div style="padding:40px;text-align:center;color:#475569;">
                    <i class="fas fa-tags" style="font-size:32px;margin-bottom:12px;"></i>
                    <p>No categories found.</p>
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Products</th>
                            <th style="text-align:right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td style="font-weight:600;color:#f1f5f9;">{{ $category->name }}</td>
                            <td style="color:#94a3b8;max-width:300px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $category->description ?: '—' }}
                            </td>
                            <td>
                                <span class="badge badge-gray">{{ $category->products_count }}</span>
                            </td>
                            <td style="text-align:right">
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-secondary btn-sm" style="padding:4px 8px;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" style="padding:4px 8px;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        @if($categories->hasPages())
        <div style="padding:16px 20px;border-top:1px solid #1e2536; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div style="color: #94a3b8; font-size: 14px;">
                Showing <span style="font-weight: 600; color: #f1f5f9;">{{ $categories->firstItem() }}</span> to <span style="font-weight: 600; color: #f1f5f9;">{{ $categories->lastItem() }}</span> of <span style="font-weight: 600; color: #f1f5f9;">{{ $categories->total() }}</span> results
            </div>
            <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 6px; color: #94a3b8; font-size: 14px;">
                    Page 
                    <input type="number" min="1" max="{{ $categories->lastPage() }}" value="{{ $categories->currentPage() }}" 
                           onkeydown="if(event.key === 'Enter' && this.value >= 1 && this.value <= {{ $categories->lastPage() }}) window.location.href='?page=' + this.value;"
                           onchange="if(this.value >= 1 && this.value <= {{ $categories->lastPage() }}) window.location.href='?page=' + this.value;"
                           style="width: 60px; padding: 4px 8px; background: #1e293b; border: 1px solid #334155; border-radius: 4px; color: #f1f5f9; text-align: center; height: 34px;">
                    of {{ $categories->lastPage() }}
                </div>
                <div style="display: flex; gap: 8px;">
                    @if ($categories->onFirstPage())
                        <span style="padding: 6px 12px; background: #0f172a; color: #64748b; border: 1px solid #1e293b; border-radius: 6px; cursor: not-allowed; font-size: 14px;">Previous</span>
                    @else
                        <a href="{{ $categories->previousPageUrl() }}" style="padding: 6px 12px; background: #1e293b; color: #f8fafc; border: 1px solid #334155; border-radius: 6px; text-decoration: none; font-size: 14px; transition: all 0.2s;">Previous</a>
                    @endif

                    @if ($categories->hasMorePages())
                        <a href="{{ $categories->nextPageUrl() }}" style="padding: 6px 12px; background: #818cf8; color: #ffffff; border-radius: 6px; text-decoration: none; font-size: 14px; transition: all 0.2s; border: 1px solid #6366f1;">Next</a>
                    @else
                        <span style="padding: 6px 12px; background: #0f172a; color: #64748b; border: 1px solid #1e293b; border-radius: 6px; cursor: not-allowed; font-size: 14px;">Next</span>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
