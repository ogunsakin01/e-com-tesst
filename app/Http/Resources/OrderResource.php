<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => UserResource::make($this->user),
            'payment_status' => $this->payment_status,
            'delivery_status' => $this->delivery_status,
            'total_price' => $this->total_price,
            'cart_items' => CartItemCollection::make($this->cartItems),
            'payment_logs' => PaymentLogCollection::make($this->paymentLogs)
        ];
    }
}
