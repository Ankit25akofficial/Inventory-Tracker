<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'sku', 'description', 'category_id', 'supplier_id', 'quantity', 'purchase_price', 'selling_price', 'image_path', 'qr_code_path', 'min_stock_level', 'shelf_location'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stockLogs()
    {
        return $this->hasMany(StockLog::class);
    }

    public function qrScans()
    {
        return $this->hasMany(QrScan::class);
    }

    public function getPredictedDepletionDaysAttribute()
    {
        $outQuantity = \App\Models\StockLog::where('product_id', $this->id)
            ->where('action', 'out')
            ->where('created_at', '>=', now()->subDays(30))
            ->sum('quantity');

        if ($outQuantity == 0) {
            return null;
        }

        $velocityPerDay = $outQuantity / 30;

        if ($velocityPerDay == 0) {
            return null;
        }

        return round($this->quantity / $velocityPerDay);
    }
}
