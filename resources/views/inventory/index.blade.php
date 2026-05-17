<x-app-layout>
    <x-slot name="title">Inventory Management</x-slot>

    @push('styles')
    <style>
        @keyframes slideUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        @media (max-width: 768px) {
            .inv-header { flex-direction: column !important; align-items: stretch !important; }
            .inv-header-actions { flex-wrap: wrap; min-width: unset !important; justify-content: stretch !important; }
            .inv-header-actions .search-wrap { max-width: 100% !important; flex: 1 1 100%; }
            .inv-header-actions .btn { flex: 1; justify-content: center; }
            /* Hide less-critical columns on mobile */
            .col-user, .col-remarks { display: none !important; }
            /* Modal full width on mobile */
            .adj-modal-card { max-width: 100% !important; margin: 16px !important; border-radius: 16px; }
        }
    </style>
    @endpush

    <div class="page-header inv-header" style="flex-wrap:wrap;gap:16px;">
        <div>
            <h1 class="page-title">Inventory Logs</h1>
            <div class="page-subtitle">Track stock movements and adjust inventory</div>
        </div>
        <div class="inv-header-actions" style="flex:1;display:flex;justify-content:flex-end;align-items:center;gap:10px;min-width:300px;flex-wrap:wrap;">
            <div class="search-wrap" style="position:relative;max-width:300px;width:100%;">
                <input type="text" id="searchInput" class="form-control" placeholder="Search logs..."
                    style="padding-left:36px;padding-right:40px;" onkeyup="filterTable()">
                <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#64748b;"></i>
                <button type="button" id="voiceBtn" title="Voice Search"
                    style="position:absolute;right:8px;top:50%;transform:translateY(-50%);background:none;border:none;color:#818cf8;cursor:pointer;padding:4px;">
                    <i class="fas fa-microphone"></i>
                </button>
            </div>
            <button onclick="document.getElementById('adjustModal').style.display='flex'" class="btn btn-primary">
                <i class="fas fa-boxes-stacked"></i> Adjust Stock
            </button>
        </div>
    </div>

    {{-- Stock Adjustment Modal --}}
    <div id="adjustModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(15,17,23,0.85);z-index:200;align-items:center;justify-content:center;padding:16px;">
        <div class="card adj-modal-card" style="width:100%;max-width:500px;animation:slideUp 0.3s ease;">
            <div class="card-header">
                <div class="card-title">Adjust Inventory</div>
                <button onclick="document.getElementById('adjustModal').style.display='none'" style="background:none;border:none;color:#64748b;cursor:pointer;font-size:18px;"><i class="fas fa-times"></i></button>
            </div>
            <div class="card-body" style="padding:24px;">
                <form action="{{ route('inventory.update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Product</label>
                        <select name="product_id" class="form-control" required id="productSelect">
                            <option value="">Select Product...</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-qty="{{ $product->quantity }}">{{ $product->name }} (SKU: {{ $product->sku }}) - Current: {{ $product->quantity }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                        <div class="form-group">
                            <label class="form-label">Action</label>
                            <select name="action" class="form-control" required id="actionSelect">
                                <option value="in">Stock In (Add)</option>
                                <option value="out">Stock Out (Deduct)</option>
                                <option value="adjustment">Set Exact Level</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" id="qtyLabel">Quantity</label>
                            <input type="number" name="quantity" class="form-control" min="0" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Remarks / Reason</label>
                        <input type="text" name="remarks" class="form-control" placeholder="e.g., Restock, Damaged, Found missing">
                    </div>

                    <div style="margin-top:24px;display:flex;justify-content:flex-end;gap:12px;">
                        <button type="button" class="btn btn-secondary" onclick="document.getElementById('adjustModal').style.display='none'">Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm Adjustment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($logs->isEmpty())
                <div style="padding:40px;text-align:center;color:#475569;">
                    <i class="fas fa-clipboard-list" style="font-size:32px;margin-bottom:12px;"></i>
                    <p>No inventory logs found.</p>
                </div>
            @else
                <table style="min-width:unset;">
                    <thead>
                        <tr>
                            <th>Date / Time</th>
                            <th>Product</th>
                            <th>Action</th>
                            <th>Qty</th>
                            <th class="col-user">User</th>
                            <th class="col-remarks">Remarks</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr>
                            <td style="color:#94a3b8;font-size:13px;white-space:nowrap;">{{ $log->created_at->format('M d, Y') }}<br><span style="font-size:11px;">{{ $log->created_at->format('H:i A') }}</span></td>
                            <td style="font-weight:600;color:#f1f5f9;">{{ $log->product->name ?? '—' }}<br><span style="font-size:11px;color:#64748b;font-weight:normal;">SKU: {{ $log->product->sku ?? '—' }}</span></td>
                            <td>
                                <span class="badge {{ $log->action === 'in' ? 'badge-success' : ($log->action === 'out' ? 'badge-danger' : 'badge-info') }}">
                                    {{ strtoupper($log->action) }}
                                    <i class="fas {{ $log->action === 'in' ? 'fa-arrow-down' : ($log->action === 'out' ? 'fa-arrow-up' : 'fa-equals') }}" style="margin-left:4px;font-size:10px;"></i>
                                </span>
                            </td>
                            <td style="font-weight:700;color:{{ $log->action === 'in' ? '#4ade80' : ($log->action === 'out' ? '#f87171' : '#f1f5f9') }}">{{ $log->action === 'out' ? '-' : '+' }}{{ $log->quantity }}</td>
                            <td class="col-user" style="color:#cbd5e1;">{{ $log->user->name ?? 'System' }}</td>
                            <td class="col-remarks" style="color:#94a3b8;font-size:13px;">{{ $log->remarks ?: '—' }}</td>
                            <td style="text-align:right;white-space:nowrap;">
                                @if($log->action === 'out')
                                    <a href="{{ route('inventory.invoice', $log) }}" class="btn btn-secondary btn-sm" style="padding:4px 8px;color:#cbd5e1;" title="Download Invoice">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        @if($logs->hasPages())
        <div style="padding:16px 20px;border-top:1px solid #1e2536; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div style="color: #94a3b8; font-size: 14px;">
                Showing <span style="font-weight: 600; color: #f1f5f9;">{{ $logs->firstItem() }}</span> to <span style="font-weight: 600; color: #f1f5f9;">{{ $logs->lastItem() }}</span> of <span style="font-weight: 600; color: #f1f5f9;">{{ $logs->total() }}</span> results
            </div>
            <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 6px; color: #94a3b8; font-size: 14px;">
                    Page 
                    <input type="number" min="1" max="{{ $logs->lastPage() }}" value="{{ $logs->currentPage() }}" 
                           onkeydown="if(event.key === 'Enter' && this.value >= 1 && this.value <= {{ $logs->lastPage() }}) window.location.href='?page=' + this.value;"
                           onchange="if(this.value >= 1 && this.value <= {{ $logs->lastPage() }}) window.location.href='?page=' + this.value;"
                           style="width: 60px; padding: 4px 8px; background: #1e293b; border: 1px solid #334155; border-radius: 4px; color: #f1f5f9; text-align: center; height: 34px;">
                    of {{ $logs->lastPage() }}
                </div>
                <div style="display: flex; gap: 8px;">
                    @if ($logs->onFirstPage())
                        <span style="padding: 6px 12px; background: #0f172a; color: #64748b; border: 1px solid #1e293b; border-radius: 6px; cursor: not-allowed; font-size: 14px;">Previous</span>
                    @else
                        <a href="{{ $logs->previousPageUrl() }}" style="padding: 6px 12px; background: #1e293b; color: #f8fafc; border: 1px solid #334155; border-radius: 6px; text-decoration: none; font-size: 14px; transition: all 0.2s;">Previous</a>
                    @endif

                    @if ($logs->hasMorePages())
                        <a href="{{ $logs->nextPageUrl() }}" style="padding: 6px 12px; background: #818cf8; color: #ffffff; border-radius: 6px; text-decoration: none; font-size: 14px; transition: all 0.2s; border: 1px solid #6366f1;">Next</a>
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
        document.getElementById('actionSelect').addEventListener('change', function(e) {
            const lbl = document.getElementById('qtyLabel');
            if(e.target.value === 'adjustment') {
                lbl.innerText = 'New Total Quantity';
            } else {
                lbl.innerText = 'Quantity to ' + (e.target.value === 'in' ? 'Add' : 'Deduct');
            }
        });

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
            if(voiceBtn) voiceBtn.style.display = 'none';
        }
    </script>
    @endpush
</x-app-layout>
