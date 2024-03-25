<?php

namespace Tests\Unit;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\PaymentLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class OrderModelTest extends TestCase
{
    public function test_can_use_payment_log_model()
    {
        $order = Order::factory()->create();
        $this->assertTrue($order instanceof Order);
    }

    public function test_has_right_columns()
    {
        $order = Order::factory()->make();
        $this->assertArrayHasKey('user_id', $order);
        $this->assertArrayHasKey('payment_status', $order);
        $this->assertArrayHasKey('delivery_status', $order);
        $this->assertArrayHasKey('total_price', $order);
    }

    public function test_can_belong_to_user()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);
        $this->assertTrue($order->user instanceof User);
        $this->assertEquals($order->user_id, $user->id);
    }

    public function test_can_have_many_cart_items()
    {
        $order = Order::factory()->create();
        CartItem::factory()->create(['order_id' => $order->id]);
        $this->assertTrue($order->cartItems instanceof Collection);
        $this->assertTrue($order->cartItems[0] instanceof CartItem);
    }

    public function test_can_have_many_payment_logs()
    {
        $order = Order::factory()->create();
        PaymentLog::factory()->create(['order_id' => $order->id]);
        $this->assertTrue($order->paymentLogs instanceof Collection);
        $this->assertTrue($order->paymentLogs[0] instanceof PaymentLog);
    }
}
