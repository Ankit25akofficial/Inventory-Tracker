<x-app-layout>
    <x-slot name="title">Reports & Analytics</x-slot>

    <style>
        .report-stat-card {
            background: linear-gradient(145deg, #1e293b, #0f172a);
            border: 1px solid #334155;
            border-radius: 16px;
            padding: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .report-stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.15);
            border-color: #4f46e5;
        }
        .rsc-content {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .rsc-title {
            color: #94a3b8;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .rsc-value {
            color: #f8fafc;
            font-size: 32px;
            font-weight: 700;
        }
        .rsc-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            box-shadow: inset 0 2px 4px rgba(255,255,255,0.1);
        }
        .rsc-icon.blue { background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; }
        .rsc-icon.green { background: linear-gradient(135deg, #10b981, #059669); color: white; }
        .rsc-icon.red { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; }
        
        .action-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .action-card:hover {
            border-color: #64748b;
        }
        .action-card-header {
            padding: 24px;
            border-bottom: 1px solid #334155;
            background: rgba(15, 23, 42, 0.3);
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .action-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #818cf8;
            background: rgba(129, 140, 248, 0.1);
        }
        .action-title {
            color: #f1f5f9;
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }
        .action-subtitle {
            color: #94a3b8;
            font-size: 13px;
            margin-top: 4px;
        }
        .action-card-body {
            padding: 24px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .action-desc {
            color: #cbd5e1;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
            border: none;
            transition: all 0.2s;
            cursor: pointer;
        }
        .btn-gradient:hover {
            background: linear-gradient(135deg, #4f46e5, #4338ca);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }
        
        .btn-pdf { background: #ef4444; border-color: #dc2626; cursor: pointer; }
        .btn-pdf:hover { background: #dc2626; transform: translateY(-2px); border-color: #b91c1c; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); }
        .btn-excel { background: #10b981; border-color: #059669; cursor: pointer; }
        .btn-excel:hover { background: #059669; transform: translateY(-2px); border-color: #047857; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); }
        
        .btn-action {
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            color: white;
            transition: all 0.2s ease;
        }
    </style>

    <div class="page-header" style="margin-bottom: 32px;">
        <div>
            <h1 class="page-title" style="font-size: 28px; font-weight: 700; background: linear-gradient(to right, #818cf8, #c084fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Reports & Analytics</h1>
            <div class="page-subtitle" style="font-size: 15px;">Generate professional insights and export your inventory data</div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(300px, 1fr));gap:24px;margin-bottom:32px;">
        <div class="report-stat-card">
            <div class="rsc-content">
                <div class="rsc-title">Total Products</div>
                <div class="rsc-value">{{ number_format($totalProducts) }}</div>
            </div>
            <div class="rsc-icon blue"><i class="fas fa-box-open"></i></div>
        </div>
        
        <div class="report-stat-card">
            <div class="rsc-content">
                <div class="rsc-title">Inventory Value</div>
                <div class="rsc-value">${{ number_format($totalValue, 2) }}</div>
            </div>
            <div class="rsc-icon green"><i class="fas fa-chart-line"></i></div>
        </div>
        
        <div class="report-stat-card">
            <div class="rsc-content">
                <div class="rsc-title">Critical Stock</div>
                <div class="rsc-value" style="color: #fca5a5;">{{ number_format($lowStock) }}</div>
            </div>
            <div class="rsc-icon red"><i class="fas fa-triangle-exclamation"></i></div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(400px, 1fr));gap:24px;">
        <div class="action-card">
            <div class="action-card-header">
                <div class="action-icon" style="color: #10b981; background: rgba(16, 185, 129, 0.1);">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div>
                    <h3 class="action-title">Data Exports</h3>
                    <div class="action-subtitle">Download your complete inventory master file</div>
                </div>
            </div>
            <div class="action-card-body">
                <p class="action-desc">
                    Generate a highly detailed snapshot of all products currently in your warehouse. Includes categories, exact quantities, pricing, minimum thresholds, and linked suppliers. Perfect for accounting or offline auditing.
                </p>
                <div style="display:flex;gap:16px;">
                    <a href="{{ route('reports.pdf') }}" class="btn-action btn-pdf" style="flex:1;">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>
                    <a href="{{ route('reports.excel') }}" class="btn-action btn-excel" style="flex:1;">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                </div>
            </div>
        </div>

        <div class="action-card">
            <div class="action-card-header">
                <div class="action-icon" style="color: #818cf8; background: rgba(99, 102, 241, 0.1);">
                    <i class="fas fa-qrcode"></i>
                </div>
                <div>
                    <h3 class="action-title">QR Label Center</h3>
                    <div class="action-subtitle">Printable sheets for physical tracking</div>
                </div>
            </div>
            <div class="action-card-body">
                <p class="action-desc">
                    Access the dedicated printing view to generate A4-ready pages containing unique QR codes for every active product in your system. Stick them on shelves or boxes for instantaneous scanning via the built-in mobile scanner.
                </p>
                <a href="{{ route('qr.print') }}" target="_blank" class="btn-action btn-gradient" style="width:100%;">
                    <i class="fas fa-print"></i> Open Print Studio
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
