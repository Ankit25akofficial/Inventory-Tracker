<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; color: #333; font-weight: bold; }
        .low-stock { color: #d9534f; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Inventory Report</h1>
    <p style="text-align:center;color:#666;">Generated on {{ date('M d, Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Supplier</th>
                <th>Qty</th>
                <th>Min Stock</th>
                <th>Price</th>
                <th>Shelf</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->sku }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name ?? 'N/A' }}</td>
                <td>{{ $product->supplier->name ?? 'N/A' }}</td>
                <td class="{{ $product->quantity <= $product->min_stock_level ? 'low-stock' : '' }}">{{ $product->quantity }}</td>
                <td>{{ $product->min_stock_level }}</td>
                <td>${{ number_format($product->selling_price, 2) }}</td>
                <td>{{ $product->shelf_location ?: 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
