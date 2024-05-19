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

            // Check if total amount is greater than 150
            if ($totalAmount > 150) {
                // Apply discount based on promo code
                $promoCode = rand(1, 10) > 5 ? 'CODE10' : 'CODE20'; // Randomly select promo code
                $discount = $promoCode === 'CODE10' ? 0.1 : 0.2; // Set discount percentage
                $totalAmount -= $totalAmount * $discount; // Calculate discounted total amount
            }

            // Create order with random status
            $status = $this->getRandomStatus();
            $order = Order::create([
                'user_id' => $cart->user_id,
                'status' => $status,
                'total' => $totalAmount,
                'payment_proof' => null,
                'shipping_service' => null,
                'tracking_number' => null,
                'order_date' => now(), // Tambahkan tanggal order
                'payment_date' => null, // Tambahkan tanggal pembayaran
                'packaging_date' => null, // Tambahkan tanggal packaging
                'shipping_date' => null, // Tambahkan tanggal shipping
                'completed_date' => null, // Tambahkan tanggal selesai
                'canceled_date' => null, // Tambahkan tanggal cancel
                'discount' => $totalAmount !== $cart->product->price * $cart->quantity ? $discount * 100 : null, // Tambahkan diskon jika ada
                'final_total' => $totalAmount, // Tambahkan total akhir
                'promo_code' => $totalAmount !== $cart->product->price * $cart->quantity ? $promoCode : null, // Tambahkan kode promo jika ada
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
                    $order->tracking_number = 'tracking_number_value'; // Set tracking numb
                    $order->payment_proof = 'payment.jpg'; // Set payment_proof file name
                    $order->shipping_date = now(); // Set packaging date
                    $order->packaging_date = now(); // Set packaging date
                    break;
                case 'completed':
                    // If shipped or completed, fill shipping_service, tracking_number, and payment_proof
                    $order->shipping_service = 'shipping_service_name'; // Set shipping service name
                    $order->tracking_number = 'tracking_number_value'; // Set tracking number
                    $order->payment_proof = 'payment.jpg'; // Set payment_proof file name
                    $order->packaging_date = now(); // Set shipping date
                    $order->shipping_date = now(); // Set packaging date
                    $order->completed_date = now(); // Set packaging date

                    // Remove cart items if the order is 'packaging', 'shipped', or 'completed'
                    $cart->delete();
                    break;
                case 'canceled':
                    // If canceled, empty payment_proof, shipping_service, and tracking_number
                    $order->canceled_date = now(); // Set cancel date
                    break;
            }

            // Save changes to the order
            $order->save();

            // You can update cart status or do any other actions here if needed
        }
    }

    /**
     * Get a random status for the order.
     *
     * @return string
     */
    private function getRandomStatus()
    {
        $statuses = ['unpaid', 'packaging', 'shipped', 'completed', 'canceled'];
        return $statuses[array_rand($statuses)];
    }
}
