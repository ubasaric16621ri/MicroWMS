<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'location_id',
        'quantity'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function add(int $amount): void
    {
        if ($amount < 0) $amount = 0;
        $this->quantity += $amount;
        $this->save();
    }

    public function subtract(int $amount): bool
    {
        if ($amount < 0) $amount = 0;

        if ($this->quantity < $amount) {
            return false;
        }

        $this->quantity -= $amount;
        $this->save();
        return true;
    }
}

