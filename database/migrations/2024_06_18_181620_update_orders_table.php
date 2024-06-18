database/migrations/xxxx_xx_xx_xxxxxx_update_orders_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('shipping_cost', 8, 2)->nullable();
            $table->decimal('total_weight', 8, 2)->nullable();
            $table->string('origin_location')->nullable();
            $table->string('destination_location')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('shipping_cost');
            $table->dropColumn('total_weight');
            $table->dropColumn('origin_location');
            $table->dropColumn('destination_location');
        });
    }
}
