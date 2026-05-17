<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\StockLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts   = Product::count();
        $totalCategories = Category::count();
        $totalSuppliers  = Supplier::count();
        $lowStockItems   = Product::whereColumn('quantity', '<=', 'min_stock_level')->count();
        $recentActivity  = StockLog::with('product')->latest()->take(10)->get();

        // 7-day chart data
        $chartLabels = [];
        $chartIn     = [];
        $chartOut    = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::now()->subDays($i);
            $chartLabels[] = $day->format('D');
            $chartIn[]     = StockLog::whereDate('created_at', $day)->where('action', 'in')->sum('quantity');
            $chartOut[]    = StockLog::whereDate('created_at', $day)->where('action', 'out')->sum('quantity');
        }

        // Category doughnut data
        $categories    = Category::withCount('products')->having('products_count', '>', 0)->get();
        $categoryLabels = $categories->pluck('name');
        $categoryData   = $categories->pluck('products_count');

        // Top Selling Products
        $topProducts = \App\Models\StockLog::with('product')
            ->where('action', 'out')
            ->selectRaw('product_id, SUM(quantity) as total_sold')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts', 'totalCategories', 'totalSuppliers',
            'lowStockItems', 'recentActivity',
            'chartLabels', 'chartIn', 'chartOut',
            'categoryLabels', 'categoryData', 'topProducts'
        ));
    }
}

