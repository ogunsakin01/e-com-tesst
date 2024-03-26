<?php

namespace App\Http\Actions;

use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddToCartItemActions
{
    private Product $product;
    private float $quantity;
    private CartItem|null $cartItem;
    private array $request;
    private float $price;

    public function addToCart(array $request): array
    {
        $this->request = $request;
        try {
            DB::beginTransaction();

            $this->getProduct();
            $this->getCartItem();
            $this->buildQuantityAndPrice();
            $this->validateProductHaveEnoughStock();
            $this->storeOrUpdateCartItem();

            DB::commit();

            return [
                'code' => 200,
                'message' => 'Cart item added successfully',
                'data' => CartItemResource::make($this->cartItem)
            ];
        } catch (\Exception $e) {
            Db::rollBack();
            return [
                'message' => $e->getMessage(),
                'code' => in_array($e->getCode(), [500, 422]) ? $e->getCode() : 400,
                'data' => ['errors' => ($e->getCode() == 500) ? $e->getTrace() : []]
            ];
        }
    }

    private function getProduct(): void
    {
        $this->product = Product::find($this->request['product_id']);
    }

    private function getCartItem(): void
    {
        $this->cartItem = CartItem::where('user_id', Auth::id())
            ->whereNull('order_id')
            ->where('product_id', $this->request['product_id'])->first();
    }

    private function buildQuantityAndPrice(): void
    {
        $this->quantity = $this->request['quantity'];
        if (!is_null($this->cartItem)) $this->quantity = $this->cartItem->quantity + $this->quantity;
        $this->price = $this->product->price * $this->quantity;
    }

    /**
     * @throws \Exception
     */
    private function validateProductHaveEnoughStock(): void
    {
        if ($this->product->quantity < $this->quantity) {
            throw new \Exception('The product does not have enough stock, reduce quantity');
        }
    }

    private function storeOrUpdateCartItem(): void
    {
        if (is_null($this->cartItem)) {
            $this->cartItem = CartItem::create([
                'quantity' => $this->quantity,
                'product_id' => $this->request['product_id'],
                'user_id' => Auth::id(),
                'old_price' => $this->price,
                'price' => $this->price,
            ]);
        } else {
            $this->cartItem->update([
                'quantity' => $this->quantity,
                'price' => $this->price,
                'old_price' => $this->price
            ]);
        }
    }
}
