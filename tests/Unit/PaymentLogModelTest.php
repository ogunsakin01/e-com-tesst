<?php

namespace Tests\Unit;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\PaymentLog;
use App\Models\User;
use Tests\TestCase;

class PaymentLogModelTest extends TestCase
{
    public function test_can_use_payment_log_model()
    {
        $paymentLog = PaymentLog::factory()->create();
        $this->assertTrue($paymentLog instanceof PaymentLog);
    }

    public function test_has_right_columns()
    {
        $paymentLog = PaymentLog::factory()->make();
        $this->assertArrayHasKey('user_id', $paymentLog);
        $this->assertArrayHasKey('order_id', $paymentLog);
        $this->assertArrayHasKey('amount', $paymentLog);
        $this->assertArrayHasKey('status', $paymentLog);
    }

    public function test_can_belong_to_user()
    {
        $user = User::factory()->create();
        $paymentLog = PaymentLog::factory()->create(['user_id' => $user->id]);
        $this->assertTrue($paymentLog->user instanceof User);
        $this->assertEquals($paymentLog->user_id, $user->id);
    }

    public function test_can_belong_to_order()
    {
        $order = Order::factory()->create();
        $paymentLog = PaymentLog::factory()->create(['order_id' => $order->id]);
        $this->assertTrue($paymentLog->order instanceof Order);
        $this->assertEquals($paymentLog->order_id, $order->id);
    }
}
