<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print QR Codes</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f1f5f9; margin: 0; padding: 20px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; }
        .card { background: #fff; padding: 20px; border-radius: 8px; text-align: center; border: 1px solid #e2e8f0; page-break-inside: avoid; }
        .qr-img { width: 150px; height: 150px; margin-bottom: 10px; }
        .sku { font-family: monospace; font-size: 14px; color: #475569; }
        .name { font-weight: bold; font-size: 16px; margin-bottom: 5px; color: #0f172a; }
        
        @media print {
            body { background: #fff; padding: 0; }
            .no-print { display: none; }
            .card { border: 1px solid #ccc; box-shadow: none; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <h2>Print Product QR Codes</h2>
        <button onclick="window.print()" style="padding: 10px 20px; background: #3b82f6; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 16px;">
            Print Now
        </button>
    </div>

    <div class="grid">
        @foreach($products as $product)
            @if(Storage::disk('public')->exists($product->qr_code_path))
                <div class="card">
                    <div class="name">{{ Str::limit($product->name, 20) }}</div>
                    <img src="{{ Storage::url($product->qr_code_path) }}" alt="QR Code" class="qr-img">
                    <div class="sku">{{ $product->sku }}</div>
                </div>
            @endif
        @endforeach
    </div>

</body>
</html>
