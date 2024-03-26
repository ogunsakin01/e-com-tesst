<?php

namespace App\Http\Actions;

use App\Http\Resources\OrderResource;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\PaymentLog;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateOrderActions
{
    private array $request;
    private Order $order;
    private Collection $cartItems;

    public function placeOrder(array $request): array
    {
        $this->request = $request;
        try {
            DB::beginTransaction();

            $this->validateCartItems();
            $this->validateProductsQuantity();
            $this->createOrder();
            $this->createPaymentLog();
            $this->updateCartItems();

            DB::commit();
            return [
                'code' => 200,
                'message' => 'Order placed successfully',
                'data' => OrderResource::make($this->order)
            ];
        } catch (Exception $e) {
            Db::rollBack();
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
    private function validateCartItems(): void
    {
        $cartItems = CartItem::query()->whereIn('id', $this->request['cart_items'])
            ->where('user_id', Auth::id())
            ->whereNotNull('order_id')->get()->pluck('id');
        if ($cartItems->count() != 0) {
            throw new Exception('Cart item with id ' . implode(', ', $cartItems->toArray()) . ' is invalid');
        } else {
            $this->cartItems = CartItem::query()->whereIn('id', $this->request['cart_items'])
                ->where('user_id', Auth::id())
                ->whereNull('order_id')->get();
        }
    }

    private function validateProductsQuantity(): void
    {
        $this->cartItems->each(function ($cartItem) {
            $productHaveEnoughQuantity = $cartItem->product->quantity > $cartItem->quantity;
            if (!$productHaveEnoughQuantity) throw new Exception('Insufficient order quantity for ' . $cartItem->product->name);
        });
    }

    private function createOrder(): void
    {
        $totalPrice = array_sum($this->cartItems->pluck('price')->toArray());
        $this->order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $totalPrice
        ]);
    }

    private function createPaymentLog(): void
    {
        $paymentLog = PaymentLog::create([
            'order_id' => $this->order->id,
            'user_id' => Auth::id(),
            'amount' => $this->order->total_price,
            'status' => 'success'
        ]);
        $this->order->update(['payment_status' => $paymentLog->status]);
    }

    private function updateCartItems(): void
    {
        $this->cartItems->each(function ($cartItem) {
            $cartItem->update(['order_id' => $this->order->id]);
        });
    }
}
