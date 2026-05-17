<x-app-layout>
    <x-slot name="title">Products</x-slot>

    @push('styles')
    <style>
        @media (max-width: 768px) {
            .prod-header { flex-direction: column !important; align-items: stretch !important; }
            .prod-header-actions { flex-wrap: wrap; min-width: unset !important; justify-content: stretch !important; }
            .prod-header-actions .search-wrap { max-width: 100% !important; flex: 1 1 100%; }
            .prod-header-actions .btn { flex: 1; justify-content: center; }
            /* Hide less-important columns on mobile */
            .col-sku, .col-category, .col-price { display: none !important; }
            /* Product name cell — remove flex so image + text wrap */
            .col-product { white-space: normal; }
        }
    </style>
    @endpush

    <div class="page-header prod-header" style="flex-wrap:wrap;gap:16px;">
        <div>
            <h1 class="page-title">Products</h1>
            <div class="page-subtitle">Manage your inventory products and QR codes</div>
        </div>
        <div class="prod-header-actions" style="flex:1;display:flex;justify-content:flex-end;align-items:center;gap:10px;min-width:300px;flex-wrap:wrap;">
            <div class="search-wrap" style="position:relative;max-width:300px;width:100%;">
                <input type="text" id="searchInput" class="form-control" placeholder="Search products..."
                    style="padding-left:36px;padding-right:40px;" onkeyup="filterTable()">
                <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#64748b;"></i>
                <button type="button" id="voiceBtn" title="Voice Search"
                    style="position:absolute;right:8px;top:50%;transform:translateY(-50%);background:none;border:none;color:#818cf8;cursor:pointer;padding:4px;">
                    <i class="fas fa-microphone"></i>
                </button>
            </div>
            <a href="{{ route('reports.excel') }}" class="btn btn-secondary" title="Export Excel" style="padding:8px 12px;">
                <i class="fas fa-file-excel" style="color:#10b981;"></i>
            </a>
            <a href="{{ route('reports.pdf') }}" class="btn btn-secondary" title="Export PDF" style="padding:8px 12px;">
                <i class="fas fa-file-pdf" style="color:#ef4444;"></i>
            </a>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Product
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($products->isEmpty())
                <div style="padding:40px;text-align:center;color:#475569;">
                    <i class="fas fa-box-open" style="font-size:32px;margin-bottom:12px;"></i>
                    <p>No products found.</p>
                </div>
            @else
                <table style="min-width:unset;">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="col-sku">SKU</th>
                            <th class="col-category">Category</th>
                            <th>Stock</th>
                            <th class="col-price">Price</th>
                            <th style="text-align:right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr class="{{ $product->quantity <= $product->min_stock_level ? 'warning-row' : '' }}">
                            <td class="col-product" style="font-weight:600;color:#f1f5f9;">
                                <div style="display:flex;align-items:center;gap:10px;">
                                    @if($product->image_path)
                                        <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" style="width:38px;height:38px;border-radius:8px;object-fit:cover;flex-shrink:0;">
                                    @else
                                        <div style="width:38px;height:38px;border-radius:8px;background:#1e2536;display:flex;align-items:center;justify-content:center;color:#64748b;flex-shrink:0;">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div>{{ $product->name }}</div>
                                        @if($product->quantity <= $product->min_stock_level)
                                            <div style="font-size:11px;color:#f87171;font-weight:normal;margin-top:2px;">Low Stock</div>
                                        @endif
                                        @php $days = $product->predicted_depletion_days; @endphp
                                        @if($days !== null && $days <= 7)
                                            <div style="font-size:11px;color:#fbbf24;font-weight:normal;margin-top:2px;">
                                                <i class="fas fa-bolt" style="font-size:10px;"></i> Empty in ~{{ $days }}d
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="col-sku" style="color:#94a3b8;font-family:monospace;">{{ $product->sku }}</td>
                            <td class="col-category"><span class="badge badge-gray">{{ $product->category->name ?? '—' }}</span></td>
                            <td style="font-weight:700;color:{{ $product->quantity <= $product->min_stock_level ? '#f87171' : '#4ade80' }}">{{ $product->quantity }}</td>
                            <td class="col-price" style="color:#cbd5e1;">${{ number_format($product->selling_price, 2) }}</td>
                            <td style="text-align:right;white-space:nowrap;">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-secondary btn-sm" style="padding:4px 8px;color:#818cf8;" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(Auth::user()->role === 'admin')
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-secondary btn-sm" style="padding:4px 8px;" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this product?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" style="padding:4px 8px;" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        @if($products->hasPages())
        <div style="padding:16px 20px;border-top:1px solid #1e2536; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div style="color: #94a3b8; font-size: 14px;">
                Showing <span style="font-weight: 600; color: #f1f5f9;">{{ $products->firstItem() }}</span> to <span style="font-weight: 600; color: #f1f5f9;">{{ $products->lastItem() }}</span> of <span style="font-weight: 600; color: #f1f5f9;">{{ $products->total() }}</span> results
            </div>
            <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 6px; color: #94a3b8; font-size: 14px;">
                    Page 
                    <input type="number" min="1" max="{{ $products->lastPage() }}" value="{{ $products->currentPage() }}" 
                           onkeydown="if(event.key === 'Enter' && this.value >= 1 && this.value <= {{ $products->lastPage() }}) window.location.href='?page=' + this.value;"
                           onchange="if(this.value >= 1 && this.value <= {{ $products->lastPage() }}) window.location.href='?page=' + this.value;"
                           style="width: 60px; padding: 4px 8px; background: #1e293b; border: 1px solid #334155; border-radius: 4px; color: #f1f5f9; text-align: center; height: 34px;">
                    of {{ $products->lastPage() }}
                </div>
                <div style="display: flex; gap: 8px;">
                    @if ($products->onFirstPage())
                        <span style="padding: 6px 12px; background: #0f172a; color: #64748b; border: 1px solid #1e293b; border-radius: 6px; cursor: not-allowed; font-size: 14px;">Previous</span>
                    @else
                        <a href="{{ $products->previousPageUrl() }}" style="padding: 6px 12px; background: #1e293b; color: #f8fafc; border: 1px solid #334155; border-radius: 6px; text-decoration: none; font-size: 14px; transition: all 0.2s;">Previous</a>
                    @endif

                    @if ($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}" style="padding: 6px 12px; background: #818cf8; color: #ffffff; border-radius: 6px; text-decoration: none; font-size: 14px; transition: all 0.2s; border: 1px solid #6366f1;">Next</a>
                    @else
                        <span style="padding: 6px 12px; background: #0f172a; color: #64748b; border: 1px solid #1e293b; border-radius: 6px; cursor: not-allowed; font-size: 14px;">Next</span>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
        function filterTable() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let rows = document.querySelectorAll("tbody tr");
            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(input) ? "" : "none";
            });
        }

        const voiceBtn = document.getElementById('voiceBtn');
        const searchInput = document.getElementById('searchInput');

        if ('webkitSpeechRecognition' in window) {
            const recognition = new webkitSpeechRecognition();
            recognition.continuous = false;
            recognition.interimResults = false;

            voiceBtn.addEventListener('click', () => {
                voiceBtn.style.color = '#ef4444'; // Red while listening
                voiceBtn.innerHTML = '<i class="fas fa-microphone-slash"></i>';
                recognition.start();
            });

            recognition.onresult = (event) => {
                const transcript = event.results[0][0].transcript;
                searchInput.value = transcript;
                filterTable();
            };

            recognition.onend = () => {
                voiceBtn.style.color = '#818cf8';
                voiceBtn.innerHTML = '<i class="fas fa-microphone"></i>';
            };
        } else {
            voiceBtn.style.display = 'none'; // Hide if not supported
        }
    </script>
    @endpush
</x-app-layout>
