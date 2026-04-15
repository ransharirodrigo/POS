<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'loyalty_points',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'loyalty_points' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'customer_id');
    }
}
