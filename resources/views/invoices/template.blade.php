<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $log->id }}</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 20px; }
        .details { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border-bottom: 1px solid #ccc; text-align: left; }
        th { background: #f4f4f4; }
        .total { text-align: right; font-size: 20px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Dispatch Invoice</h1>
        <p>Date: {{ $log->created_at->format('M d, Y') }} | Invoice #: INV-{{ str_pad($log->id, 5, '0', STR_PAD_LEFT) }}</p>
    </div>
    
    <div class="details">
        <p><strong>Processed By:</strong> {{ $log->user->name ?? 'System' }}</p>
        <p><strong>Remarks/Reason:</strong> {{ $log->remarks ?: 'N/A' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item Description</th>
                <th>SKU</th>
                <th>Unit Price</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $log->product->name }}</td>
                <td>{{ $log->product->sku }}</td>
                <td>${{ number_format($log->product->selling_price, 2) }}</td>
                <td>{{ $log->quantity }}</td>
                <td>${{ number_format($log->product->selling_price * $log->quantity, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        Grand Total: ${{ number_format($log->product->selling_price * $log->quantity, 2) }}
    </div>

    <div style="margin-top: 50px; text-align: center; font-size: 12px; color: #777;">
        <p>Thank you for your business!</p>
        <p>This is a computer-generated document and requires no signature.</p>
    </div>
</body>
</html>
