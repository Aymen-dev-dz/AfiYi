<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\DestinyConnection;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestinyConnectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_destiny_connection_is_created_when_order_is_completed()
    {
        $user = User::factory()->create();
        $order = Order::create([
            'reference' => 'TEST-123',
            'user_id' => $user->id,
            'total_price' => 10.00,
            'status' => 'completed'
        ]);
        
        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => 1,
            'seller_id' => 1,
            'quantity' => 1,
            'unit_price' => 10.00,
            'subtotal' => 10.00,
            'destiny_token' => \Illuminate\Support\Str::random(10)
        ]);
        
        DestinyConnection::create([
            'user_id' => $user->id,
            'order_item_id' => $item->id,
            'token' => $item->destiny_token,
            'status' => 'active'
        ]);

        $this->assertDatabaseHas('destiny_connections', [
            'user_id' => $user->id,
            'token' => $item->destiny_token
        ]);
    }
}
