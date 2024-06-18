<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalColumnsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('weight', 8, 2)->default(0)->after('image'); // Menambahkan nilai default
            $table->decimal('length', 8, 2)->nullable()->after('weight');
            $table->decimal('width', 8, 2)->nullable()->after('length');
            $table->decimal('height', 8, 2)->nullable()->after('width');
            $table->string('status')->default('available')->after('height');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['weight', 'length', 'width', 'height', 'status']);
        });
    }
}
