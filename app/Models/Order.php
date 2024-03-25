<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class, 'order_id','id');
    }

    public function paymentLogs(): HasMany
    {
        return $this->hasMany(PaymentLog::class, 'order_id', 'id');
    }
}
