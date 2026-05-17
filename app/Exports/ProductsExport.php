<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Product::with(['category', 'supplier'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'SKU',
            'Category',
            'Supplier',
            'Quantity',
            'Min Stock Level',
            'Purchase Price',
            'Selling Price',
            'Shelf Location',
            'Created At',
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->sku,
            $product->category ? $product->category->name : 'N/A',
            $product->supplier ? $product->supplier->name : 'N/A',
            $product->quantity,
            $product->min_stock_level,
            $product->purchase_price,
            $product->selling_price,
            $product->shelf_location,
            $product->created_at->format('Y-m-d H:i:s'),
        ];
    }
}

