<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    {{-- Stats Cards --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-box"></i></div>
            <div>
                <div class="stat-value">{{ $totalProducts }}</div>
                <div class="stat-label">Total Products</div>
                <div class="stat-change"><i class="fas fa-arrow-up"></i> Active SKUs</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-tags"></i></div>
            <div>
                <div class="stat-value">{{ $totalCategories }}</div>
                <div class="stat-label">Categories</div>
                <div class="stat-change"><i class="fas fa-layer-group"></i> Organized</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-truck"></i></div>
            <div>
                <div class="stat-value">{{ $totalSuppliers }}</div>
                <div class="stat-label">Suppliers</div>
                <div class="stat-change"><i class="fas fa-handshake"></i> Active vendors</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-triangle-exclamation"></i></div>
            <div>
                <div class="stat-value">{{ $lowStockItems }}</div>
                <div class="stat-label">Low Stock Alerts</div>
                <div class="stat-change" style="color:{{ $lowStockItems > 0 ? '#f87171' : '#4ade80' }}">
                    {{ $lowStockItems > 0 ? 'Needs restocking' : 'All stocked up' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="charts-grid">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-chart-line" style="color:#6366f1;margin-right:8px;"></i>Stock Activity (Last 7 Days)</div>
            </div>
            <div class="chart-container">
                <canvas id="activityChart"></canvas>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-chart-pie" style="color:#8b5cf6;margin-right:8px;"></i>Stock by Category</div>
            </div>
            <div class="chart-container">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Bottom Row --}}
    <div style="display:grid;grid-template-columns:1.8fr 1fr 1fr;gap:20px;">
        {{-- Recent Activity --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-clock-rotate-left" style="color:#6366f1;margin-right:8px;"></i>Recent Activity</div>
                <a href="{{ route('inventory.index') }}" class="btn btn-secondary btn-sm">View</a>
            </div>
            <div class="card-body">
                @if($recentActivity->isEmpty())
                    <div style="padding:40px;text-align:center;color:#475569;">
                        <i class="fas fa-inbox" style="font-size:32px;margin-bottom:12px;"></i>
                        <p>No stock activity yet.</p>
                    </div>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Action</th>
                                <th>Qty</th>
                                <th>When</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentActivity->take(6) as $log)
                            <tr>
                                <td>{{ $log->product->name ?? '—' }}</td>
                                <td>
                                    <span class="badge {{ $log->action === 'in' ? 'badge-success' : ($log->action === 'out' ? 'badge-danger' : 'badge-info') }}">
                                        {{ strtoupper($log->action) }}
                                    </span>
                                </td>
                                <td>{{ $log->quantity }}</td>
                                <td style="color:#475569;font-size:12px;">{{ $log->created_at->diffForHumans() }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        {{-- Top Selling Products --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-fire" style="color:#f59e0b;margin-right:8px;"></i>Top Selling</div>
            </div>
            <div class="card-body">
                @if(isset($topProducts) && $topProducts->isEmpty())
                    <div style="padding:40px;text-align:center;color:#475569;">
                        <i class="fas fa-chart-bar" style="font-size:32px;margin-bottom:12px;"></i>
                        <p>No sales data.</p>
                    </div>
                @else
                    <table>
                        <thead>
                            <tr><th>Product</th><th>Sold</th></tr>
                        </thead>
                        <tbody>
                            @foreach($topProducts as $top)
                            <tr>
                                <td style="font-weight:600;color:#f1f5f9;">{{ $top->product->name ?? '—' }}</td>
                                <td style="color:#4ade80;font-weight:700;">+{{ $top->total_sold }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        {{-- Low Stock Products --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-triangle-exclamation" style="color:#f87171;margin-right:8px;"></i>Low Stock</div>
                <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">View</a>
            </div>
            <div class="card-body">
                @php $lowStock = \App\Models\Product::with('category')->whereColumn('quantity','<=','min_stock_level')->latest()->take(6)->get(); @endphp
                @if($lowStock->isEmpty())
                    <div style="padding:40px;text-align:center;color:#475569;">
                        <i class="fas fa-circle-check" style="font-size:32px;color:#4ade80;margin-bottom:12px;"></i>
                        <p>All well stocked!</p>
                    </div>
                @else
                    <table>
                        <thead>
                            <tr><th>Product</th><th>Qty</th><th>Min</th></tr>
                        </thead>
                        <tbody>
                            @foreach($lowStock as $p)
                            <tr class="warning-row">
                                <td>{{ $p->name }}</td>
                                <td style="color:#f87171;font-weight:700;">{{ $p->quantity }}</td>
                                <td style="color:#64748b;">{{ $p->min_stock_level }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    // Activity Line Chart
    const actCtx = document.getElementById('activityChart').getContext('2d');
    
    // Create rich gradients for line fills
    let gradientIn = actCtx.createLinearGradient(0, 0, 0, 300);
    gradientIn.addColorStop(0, 'rgba(16, 185, 129, 0.4)'); // Emerald
    gradientIn.addColorStop(1, 'rgba(16, 185, 129, 0.0)');
    
    let gradientOut = actCtx.createLinearGradient(0, 0, 0, 300);
    gradientOut.addColorStop(0, 'rgba(239, 68, 68, 0.4)'); // Red
    gradientOut.addColorStop(1, 'rgba(239, 68, 68, 0.0)');

    new Chart(actCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels ?? ['Mon','Tue','Wed','Thu','Fri','Sat','Sun']) !!},
            datasets: [{
                label: 'Stock In',
                data: {!! json_encode($chartIn ?? [0,0,0,0,0,0,0]) !!},
                borderColor: '#10b981', 
                backgroundColor: gradientIn,
                borderWidth: 3,
                tension: 0.4, 
                fill: true, 
                pointBackgroundColor: '#10b981', 
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 7,
            },{
                label: 'Stock Out',
                data: {!! json_encode($chartOut ?? [0,0,0,0,0,0,0]) !!},
                borderColor: '#ef4444', 
                backgroundColor: gradientOut,
                borderWidth: 3,
                tension: 0.4, 
                fill: true, 
                pointBackgroundColor: '#ef4444', 
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true, 
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: { 
                legend: { 
                    position: 'top',
                    labels: { color: '#94a3b8', font: { family: 'Outfit', size: 13, weight: '500' }, usePointStyle: true, pointStyle: 'circle' } 
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleColor: '#f1f5f9',
                    bodyColor: '#cbd5e1',
                    borderColor: 'rgba(255,255,255,0.1)',
                    borderWidth: 1,
                    padding: 12,
                    boxPadding: 6,
                    usePointStyle: true,
                    titleFont: { family: 'Outfit', size: 14, weight: 'bold' },
                    bodyFont: { family: 'Outfit', size: 13 }
                }
            },
            scales: {
                x: { 
                    grid: { display: false }, 
                    ticks: { color: '#64748b', font: { family: 'Outfit' } } 
                },
                y: { 
                    border: { display: false },
                    grid: { color: 'rgba(255,255,255,0.05)', borderDash: [5, 5] }, 
                    ticks: { color: '#64748b', font: { family: 'Outfit' } }, 
                    beginAtZero: true 
                }
            }
        }
    });

    // Category Doughnut Chart
    const catCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(catCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($categoryLabels ?? ['No Data']) !!},
            datasets: [{
                data: {!! json_encode($categoryData ?? [1]) !!},
                backgroundColor: ['#8b5cf6', '#3b82f6', '#06b6d4', '#10b981', '#f59e0b', '#ef4444', '#ec4899'],
                borderColor: '#161b27', 
                borderWidth: 0,
                hoverOffset: 12
            }]
        },
        options: {
            responsive: true, 
            maintainAspectRatio: false,
            layout: { padding: 10 },
            plugins: {
                legend: { 
                    position: 'bottom', 
                    labels: { color: '#94a3b8', font: { family: 'Outfit', size: 13 }, padding: 20, usePointStyle: true, pointStyle: 'circle' } 
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleColor: '#f1f5f9',
                    bodyColor: '#cbd5e1',
                    borderColor: 'rgba(255,255,255,0.1)',
                    borderWidth: 1,
                    padding: 12,
                    boxPadding: 6,
                    usePointStyle: true,
                    titleFont: { family: 'Outfit', size: 14, weight: 'bold' },
                    bodyFont: { family: 'Outfit', size: 13 }
                }
            },
            cutout: '70%'
        }
    });
    </script>
    @endpush
</x-app-layout>
