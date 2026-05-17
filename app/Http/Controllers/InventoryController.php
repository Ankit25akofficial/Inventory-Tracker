<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $logs = StockLog::with(['product', 'user'])->latest()->paginate(15);

        return view('inventory.index', compact('products', 'logs'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'action' => 'required|in:in,out,adjustment',
            'quantity' => 'required|integer|min:1',
            'remarks' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($request->product_id);
            $quantity = $request->quantity;

            if ($request->action === 'out') {
                if ($product->quantity < $quantity) {
                    return back()->with('error', 'Not enough stock available for this operation.');
                }
                $product->quantity -= $quantity;
            } elseif ($request->action === 'in') {
                $product->quantity += $quantity;
            } elseif ($request->action === 'adjustment') {
                $diff = $quantity - $product->quantity;

                if ($diff == 0) {
                    return back()->with('success', 'Stock level unchanged.');
                }

                $logAction = $diff > 0 ? 'in' : 'out';
                $logQuantity = abs($diff);

                $product->quantity = $quantity;

                StockLog::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'action' => $logAction,
                    'quantity' => $logQuantity,
                    'remarks' => $request->remarks ?: 'Manual stock adjustment',
                ]);

                $product->save();

                if ($product->quantity <= $product->min_stock_level) {
                    $admins = \App\Models\User::where('role', 'admin')->get();
                    \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\LowStockNotification($product));
                }

                DB::commit();

                return back()->with('success', 'Stock adjusted successfully.');
            }

            $product->save();

            if ($product->quantity <= $product->min_stock_level) {
                $admins = \App\Models\User::where('role', 'admin')->get();
                \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\LowStockNotification($product));
            }

            StockLog::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'action' => $request->action,
                'quantity' => $quantity,
                'remarks' => $request->remarks,
            ]);

            DB::commit();

            return back()->with('success', 'Inventory updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while updating inventory.');
        }
    }

    public function invoice(StockLog $log)
    {
        if ($log->action !== 'out') {
            return back()->with('error', 'Invoices can only be generated for stock dispatch (Out).');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.template', compact('log'));
        return $pdf->download('INV-'.str_pad($log->id, 5, '0', STR_PAD_LEFT).'.pdf');
    }
}

