<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total', 8, 2);
            $table->enum('status', ['unpaid', 'paid', 'packaging', 'shipped', 'completed', 'canceled'])->default('unpaid');
            $table->string('payment_proof')->nullable();
            $table->string('shipping_service')->nullable();
            $table->string('tracking_number')->nullable();
            $table->dateTime('order_date')->nullable(); // Tambahkan kolom tanggal order
            $table->dateTime('payment_date')->nullable(); // Tambahkan kolom tanggal pembayaran
            $table->dateTime('packaging_date')->nullable(); // Tambahkan kolom tanggal packaging
            $table->dateTime('shipping_date')->nullable(); // Tambahkan kolom tanggal shipping
            $table->dateTime('completed_date')->nullable(); // Tambahkan kolom tanggal selesai
            $table->dateTime('canceled_date')->nullable(); // Tambahkan kolom tanggal cancel
            $table->decimal('discount', 8, 2)->nullable(); // Tambahkan kolom diskon
            $table->decimal('final_total', 8, 2)->nullable(); // Tambahkan kolom total akhir
            $table->string('promo_code')->nullable(); // Tambahkan kolom kode promo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
