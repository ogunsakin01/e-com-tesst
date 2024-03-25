<?php

namespace Tests\Unit;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\PaymentLog;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    public function test_can_use_user_model()
    {
        $user = User::factory()->create();
        $this->assertTrue($user instanceof User);
    }

    public function test_has_right_columns(){
        $user = User::factory()->make();
        $this->assertArrayHasKey('name',$user);
        $this->assertArrayHasKey('email',$user);
    }

    public function test_can_have_cart_items()
    {
        $user = User::factory()->create();
        CartItem::factory()->create(['user_id' => $user->id]);
        $this->assertTrue($user->cartItems instanceof Collection);
        $this->assertTrue($user->cartItems[0] instanceof CartItem);
    }

    public function test_can_have_orders()
    {
        $user = User::factory()->create();
        Order::factory()->create(['user_id' => $user->id]);
        $this->assertTrue($user->orders instanceof Collection);
        $this->assertTrue($user->orders[0] instanceof Order);
    }

    public function test_can_have_products()
    {
        $user = User::factory()->create();
        Product::factory()->create(['user_id' => $user->id]);
        $this->assertTrue($user->products instanceof Collection);
        $this->assertTrue($user->products[0] instanceof Product);
    }

    public function test_can_have_payment_logs()
    {
        $user = User::factory()->create();
        PaymentLog::factory()->create(['user_id' => $user->id]);
        $this->assertTrue($user->paymentLogs instanceof Collection);
        $this->assertTrue($user->paymentLogs[0] instanceof PaymentLog);
    }
}
