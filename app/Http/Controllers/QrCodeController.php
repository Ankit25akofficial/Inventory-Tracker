<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\QrScan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class QrCodeController extends Controller
{
    public function scanner()
    {
        return view('qr.scanner');
    }

    public function handleScan(Request $request)
    {
        $request->validate([
            'sku' => 'required|string',
            'action' => 'required|in:check,in,out',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $skuInput = $request->sku;
        
        // If the scanned text is a full URL, try to extract the sku query parameter
        if (filter_var($skuInput, FILTER_VALIDATE_URL)) {
            $parsedUrl = parse_url($skuInput);
            if (isset($parsedUrl['query'])) {
                parse_str($parsedUrl['query'], $queryVars);
                if (isset($queryVars['sku'])) {
                    $skuInput = $queryVars['sku'];
                }
            }
        }

        $product = Product::where('sku', $skuInput)->first();

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found for this QR code.']);
        }

        $quantity = $request->quantity ?? 1;

        if ($request->action === 'in') {
            $product->quantity += $quantity;
            $product->save();
            $this->logInventory($product->id, 'in', $quantity, 'Scanned QR Code (Stock In)');
        } elseif ($request->action === 'out') {
            if ($product->quantity < $quantity) {
                return response()->json(['success' => false, 'message' => 'Not enough stock.']);
            }
            $product->quantity -= $quantity;
            $product->save();
            $this->logInventory($product->id, 'out', $quantity, 'Scanned QR Code (Stock Out)');
        }

        // Log scan event
        QrScan::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'scan_type' => $request->action,
            'device_info' => $request->header('User-Agent'),
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Scan successful.',
            'product' => [
                'name' => $product->name,
                'sku' => $product->sku,
                'quantity' => $product->quantity,
                'price' => number_format($product->selling_price, 2),
                'url' => route('products.show', $product->id)
            ]
        ]);
    }

    private function logInventory($productId, $action, $quantity, $remarks)
    {
        \App\Models\StockLog::create([
            'product_id' => $productId,
            'user_id' => Auth::id(),
            'action' => $action,
            'quantity' => $quantity,
            'remarks' => $remarks,
        ]);
    }

    public function download(Product $product)
    {
        if (!$product->qr_code_path || !Storage::disk('public')->exists($product->qr_code_path)) {
            return back()->with('error', 'QR code not found.');
        }

        return Storage::disk('public')->download($product->qr_code_path, 'QR-' . $product->sku . '.svg');
    }

    public function printAll()
    {
        $products = Product::whereNotNull('qr_code_path')->get();
        return view('qr.print', compact('products'));
    }
}

