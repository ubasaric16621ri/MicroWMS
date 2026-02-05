<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type'
    ];

    public function isPick(): bool
    {
        return $this->type === 'Pick';
    }

    public function isBulk(): bool
    {
        return $this->type === 'Bulk';
    }

    public function isReceiving(): bool
    {
        return $this->type === 'Receiving';
    }
}

