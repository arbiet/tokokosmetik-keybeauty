<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Retrieve 10 carts from users with usertype 'customer'
        $carts = Cart::whereHas('user', function ($query) {
            $query->where('usertype', 'customer');
        })->take(10)->get();

        // Create orders from carts
        foreach ($carts as $cart) {
            // Calculate total amount based on product price and quantity
            $totalAmount = $cart->product->price * $cart->quantity;
            $totalWeight = $cart->product->weight * $cart->quantity / 1000;

            // Check if total amount is greater than 150
            if ($totalAmount > 150) {
                // Apply discount based on promo code
                $promoCode = rand(1, 10) > 5 ? 'CODE10' : 'CODE20'; // Randomly select promo code
                $discount = $promoCode === 'CODE10' ? 0.1 : 0.2; // Set discount percentage
                $discountAmount = $totalAmount * $discount;
                $totalAmount -= $discountAmount; // Calculate discounted total amount
            } else {
                $promoCode = null;
                $discount = null;
                $discountAmount = 0;
            }

            // Create order with random status
            $status = $this->getRandomStatus();
            $order = Order::create([
                'user_id' => $cart->user_id,
                'status' => $status,
                'total' => $totalAmount + $discountAmount, // Total before discount
                'payment_proof' => null,
                'shipping_service' => null,
                'tracking_number' => null,
                'order_date' => now(),
                'payment_date' => null,
                'packaging_date' => null,
                'shipping_date' => null,
                'completed_date' => null,
                'canceled_date' => null,
                'discount' => $discount ? $discount * 100 : null, // Discount in percentage
                'final_total' => $totalAmount, // Total after discount
                'promo_code' => $promoCode,
                'shipping_cost' => 10.00, // Example shipping cost, you can modify as needed
                'total_weight' => $totalWeight,
                'origin_location' => 'Toko Kosmetik Keybeauty, Nganjuk', // Example origin location
                'destination_location' => $cart->user->addresses()->first() ? $cart->user->addresses()->first()->full_address : 'Unknown Address', // Example destination location
            ]);

            // Create order items from cart items
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cart->product_id,
                'quantity' => $cart->quantity,
                'price' => $cart->product->price, // Set the price of the product
            ]);

            // Update order fields based on status
            switch ($status) {
                case 'unpaid':
                    // If unpaid, leave payment_proof empty
                    break;
                case 'packaging':
                    // If packaging, empty shipping_service and tracking_number, and set payment_proof
                    $order->payment_proof = 'payment.jpg'; // Set payment_proof file name
                    $order->packaging_date = now(); // Set packaging date
                    break;
                case 'shipped':
                    $order->shipping_service = 'shipping_service_name'; // Set shipping service name
                    $order->tracking_number = 'tracking_number_value'; // Set tracking number
                    $order->payment_proof = 'payment.jpg'; // Set payment_proof file name
                    $order->shipping_date = now(); // Set shipping date
                    $order->packaging_date = now(); // Set packaging date
                    break;
                case 'completed':
                    // If shipped or completed, fill shipping_service, tracking_number, and payment_proof
                    $order->shipping_service = 'shipping_service_name'; // Set shipping service name
                    $order->tracking_number = 'tracking_number_value'; // Set tracking number
                    $order->payment_proof = 'payment.jpg'; // Set payment_proof file name
                    $order->packaging_date = now(); // Set packaging date
                    $order->shipping_date = now(); // Set shipping date
                    $order->completed_date = now(); // Set completed date
                    break;
                case 'canceled':
                    // If canceled, empty payment_proof, shipping_service, and tracking_number
                    $order->canceled_date = now(); // Set canceled date
                    break;
            }

            // Save changes to the order
            $order->save();

            // Remove cart items if the order is 'packaging', 'shipped', or 'completed'
            if (in_array($status, ['packaging', 'shipped', 'completed'])) {
                $cart->delete();
            }
        }
    }

    /**
     * Get a random status for the order.
     *
     * @return string
     */
    private function getRandomStatus()
    {
        $statuses = ['unpaid', 'packaging', 'shipped', 'completed', 'cancelled'];
        return $statuses[array_rand($statuses)];
    }
}
