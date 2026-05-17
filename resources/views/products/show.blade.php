<x-app-layout>
    <x-slot name="title">{{ $product->name }}</x-slot>

    <div class="page-header">
        <div>
            <h1 class="page-title">{{ $product->name }}</h1>
            <div class="page-subtitle">SKU: {{ $product->sku }} | Category: {{ $product->category->name ?? '—' }}</div>
        </div>
        <div>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            @if(Auth::user()->role === 'admin')
            <a href="{{ route('products.edit', $product) }}" class="btn btn-primary" style="margin-left:8px;">
                <i class="fas fa-edit"></i> Edit Product
            </a>
            <a href="{{ route('qr.download', $product) }}" class="btn btn-secondary" style="margin-left:8px;color:#10b981;border-color:rgba(16,185,129,0.3);">
                <i class="fas fa-download"></i> Download QR
            </a>
            @endif
        </div>
    </div>

    @push('styles')
    <style>
        /* ── Staggered Card Entrance ── */
        .animated-card {
            opacity: 0;
            animation: fadeUp 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── QR Code Hover & Float ── */
        .qr-container {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            animation: float 6s ease-in-out infinite;
            position: relative;
        }
        .qr-container:hover {
            transform: scale(1.08) translateY(-5px);
            box-shadow: 0 20px 40px rgba(16, 185, 129, 0.3);
            animation-play-state: paused;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
            100% { transform: translateY(0px); }
        }

        /* ── Image Placeholder Shimmer ── */
        .image-placeholder {
            background: linear-gradient(90deg, #1e2536 25%, #2d3748 50%, #1e2536 75%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite linear;
            box-shadow: inset 0 0 20px rgba(0,0,0,0.5);
        }
        @keyframes shimmer {
            from { background-position: 200% 0; }
            to { background-position: -200% 0; }
        }
        .product-image {
            transition: transform 0.5s ease;
        }
        .product-image:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        /* ── Info Blocks Hover ── */
        .info-block {
            transition: all 0.3s ease;
            padding: 12px 16px;
            border-radius: 12px;
            background: rgba(255,255,255,0.01);
            border: 1px solid transparent;
            position: relative; overflow: hidden;
        }
        .info-block:hover {
            background: rgba(99, 102, 241, 0.04);
            border-color: rgba(99, 102, 241, 0.2);
            transform: translateX(6px);
        }
        .info-block::before {
            content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 3px;
            background: linear-gradient(to bottom, #6366f1, #c084fc);
            transform: scaleY(0); transition: transform 0.3s ease; transform-origin: bottom;
        }
        .info-block:hover::before { transform: scaleY(1); }

        /* ── Table Row Hover ── */
        .activity-row { transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .activity-row:hover {
            transform: scale(1.01) translateX(8px);
            background: rgba(99, 102, 241, 0.08) !important;
            box-shadow: -4px 0 0 #6366f1;
        }
    </style>
    @endpush

    <div style="display:grid;grid-template-columns:1fr 300px;gap:24px;">
        <div style="display:flex;flex-direction:column;gap:24px;">
            <div class="card animated-card delay-1">
                <div class="card-header">
                    <div class="card-title">Product Details</div>
                </div>
                <div class="card-body" style="padding:24px;">
                    <div style="display:grid;grid-template-columns:150px 1fr;gap:20px;">
                        @if($product->image_path)
                            <div style="overflow:hidden;border-radius:12px;border:1px solid #1e2536;aspect-ratio:1;">
                                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="product-image" style="width:100%;height:100%;object-fit:cover;">
                            </div>
                        @else
                            <div class="image-placeholder" style="width:100%;aspect-ratio:1;border-radius:12px;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.1);font-size:48px;">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                        
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;align-content:start;">
                            <div class="info-block">
                                <div style="font-size:12px;color:#64748b;font-weight:600;margin-bottom:4px;">Stock Quantity</div>
                                <div style="font-size:24px;font-weight:800;color:{{ $product->quantity <= $product->min_stock_level ? '#f87171' : '#4ade80' }}">{{ $product->quantity }}</div>
                                @if($product->quantity <= $product->min_stock_level)
                                    <div style="font-size:11px;color:#f87171;margin-top:4px;animation:pulse-red 2s infinite;"><i class="fas fa-triangle-exclamation"></i> Low Stock Alert</div>
                                @endif
                            </div>
                            <div class="info-block">
                                <div style="font-size:12px;color:#64748b;font-weight:600;margin-bottom:4px;">Shelf Location</div>
                                <div style="font-size:16px;color:#f1f5f9;font-weight:600;">{{ $product->shelf_location ?: 'Not specified' }}</div>
                            </div>
                            <div class="info-block">
                                <div style="font-size:12px;color:#64748b;font-weight:600;margin-bottom:4px;">Purchase Price</div>
                                <div style="font-size:16px;color:#f1f5f9;">${{ number_format($product->purchase_price, 2) }}</div>
                            </div>
                            <div class="info-block">
                                <div style="font-size:12px;color:#64748b;font-weight:600;margin-bottom:4px;">Selling Price</div>
                                <div style="font-size:16px;color:#f1f5f9;">${{ number_format($product->selling_price, 2) }}</div>
                            </div>
                            <div class="info-block" style="grid-column:1/-1;">
                                <div style="font-size:12px;color:#64748b;font-weight:600;margin-bottom:4px;">Description</div>
                                <div style="font-size:14px;color:#cbd5e1;line-height:1.6;">{{ $product->description ?: 'No description provided.' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card animated-card delay-2">
                <div class="card-header">
                    <div class="card-title">Recent Stock Activity</div>
                </div>
                <div class="card-body">
                    @php $logs = $product->stockLogs()->with('user')->latest()->take(5)->get(); @endphp
                    @if($logs->isEmpty())
                        <div style="padding:30px;text-align:center;color:#475569;">No stock logs found for this product.</div>
                    @else
                        <table>
                            <thead><tr><th>Action</th><th>Qty</th><th>User</th><th>Remarks</th><th>Date</th></tr></thead>
                            <tbody>
                                @foreach($logs as $log)
                                <tr class="activity-row">
                                    <td><span class="badge {{ $log->action === 'in' ? 'badge-success' : ($log->action === 'out' ? 'badge-danger' : 'badge-info') }}">{{ strtoupper($log->action) }}</span></td>
                                    <td>{{ $log->quantity }}</td>
                                    <td>{{ $log->user->name ?? 'System' }}</td>
                                    <td>{{ $log->remarks ?: '—' }}</td>
                                    <td style="color:#64748b;font-size:12px;">{{ $log->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:24px;">
            <div class="card animated-card delay-3 text-center" style="text-align:center;">
                <div class="card-header" style="justify-content:center;">
                    <div class="card-title">QR Code</div>
                </div>
                <div class="card-body" style="padding:30px 24px;display:flex;flex-direction:column;align-items:center;">
                    @if($product->qr_code_path && Storage::disk('public')->exists($product->qr_code_path))
                        <div class="qr-container" style="background:#fff;padding:16px;border-radius:16px;display:inline-block;margin-bottom:16px;border:4px solid rgba(16,185,129,0.1);">
                            <img src="{{ Storage::url($product->qr_code_path) }}" alt="QR Code" style="width:180px;height:180px;">
                        </div>
                        <div style="font-family:monospace;color:#f1f5f9;font-weight:700;font-size:15px;background:rgba(99,102,241,0.1);padding:8px 20px;border-radius:20px;border:1px solid rgba(99,102,241,0.3);box-shadow:inset 0 0 10px rgba(99,102,241,0.1);">
                            {{ $product->sku }}
                        </div>
                    @else
                        <div style="color:#f87171;margin-bottom:16px;"><i class="fas fa-triangle-exclamation" style="font-size:32px;margin-bottom:12px;"></i><br>QR Code not generated</div>
                    @endif
                </div>
            </div>

            <div class="card animated-card delay-4">
                <div class="card-header">
                    <div class="card-title">Supplier Info</div>
                </div>
                <div class="card-body" style="padding:24px;">
                    @if($product->supplier)
                        <div style="font-weight:700;color:#f1f5f9;margin-bottom:12px;font-size:16px;display:flex;align-items:center;gap:8px;">
                            <i class="fas fa-truck-fast" style="color:#6366f1;"></i> {{ $product->supplier->name }}
                        </div>
                        <div style="font-size:13px;color:#94a3b8;display:flex;flex-direction:column;gap:12px;">
                            @if($product->supplier->company_name)
                                <div class="info-block" style="padding:8px;border-radius:8px;"><i class="fas fa-building" style="width:20px;color:#818cf8;"></i> {{ $product->supplier->company_name }}</div>
                            @endif
                            @if($product->supplier->email)
                                <div class="info-block" style="padding:8px;border-radius:8px;"><i class="fas fa-envelope" style="width:20px;color:#818cf8;"></i> {{ $product->supplier->email }}</div>
                            @endif
                            @if($product->supplier->phone)
                                <div class="info-block" style="padding:8px;border-radius:8px;"><i class="fas fa-phone" style="width:20px;color:#818cf8;"></i> {{ $product->supplier->phone }}</div>
                            @endif
                        </div>
                    @else
                        <div style="color:#475569;text-align:center;padding:20px;">
                            <i class="fas fa-truck-slash" style="font-size:24px;margin-bottom:8px;opacity:0.5;"></i><br>
                            No supplier assigned.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
