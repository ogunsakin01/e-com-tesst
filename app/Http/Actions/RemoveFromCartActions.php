<?php

namespace App\Http\Actions;

use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RemoveFromCartActions
{
    private CartItem|null $cartItem;
    private array $request;

    public function removeFromCart(array $request): array
    {
        $this->request = $request;
        try {
            DB::beginTransaction();
            $this->checkIfValidCartItem();
            $this->updateQuantityAndPrice();

            DB::commit();

            return [
                'code' => 200,
                'message' => !is_null($this->cartItem) ? 'Cart item updated' : 'Cart item deleted',
                'data' => !is_null($this->cartItem) ? CartItemResource::make($this->cartItem) : []
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'message' => $e->getMessage(),
                'code' => in_array($e->getCode(), [500, 422]) ? $e->getCode() : 400,
                'data' => ['errors' => ($e->getCode() == 500) ? $e->getTrace() : []]
            ];
        }
    }

    /**
     * @throws Exception
     */
    private function checkIfValidCartItem(): void
    {
        $this->cartItem = CartItem::where('product_id', $this->request['product_id'])
            ->where('user_id', Auth::id())
            ->whereNull('order_id')->first();
        if (is_null($this->cartItem)) throw new Exception('Cart item no longer available');
    }

    private function updateQuantityAndPrice(): void
    {
        $newQuantity = $this->cartItem->quantity - $this->request['quantity'];
        if ($newQuantity < 1) $this->cartItem = $this->cartItem->delete() ? null : [];
        else {
            $newPrice = $this->cartItem->product->price * $newQuantity;
            $this->cartItem->update(['quantity' => $newQuantity, 'price' => $newPrice, 'old_price' => $newPrice]);
        }
    }
}
