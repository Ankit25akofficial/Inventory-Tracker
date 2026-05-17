<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;

class ReportController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalValue = Product::sum(\Illuminate\Support\Facades\DB::raw('quantity * purchase_price'));
        $lowStock = Product::whereColumn('quantity', '<=', 'min_stock_level')->count();

        return view('reports.index', compact('totalProducts', 'totalValue', 'lowStock'));
    }

    public function exportPdf()
    {
        $products = Product::with(['category', 'supplier'])->get();
        $pdf = Pdf::loadView('reports.pdf', compact('products'));
        return $pdf->download('inventory-report-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new ProductsExport, 'inventory-report-'.date('Y-m-d').'.xlsx');
    }
}

