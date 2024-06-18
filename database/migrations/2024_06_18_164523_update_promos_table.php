<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->dropColumn('discount_amount');
            $table->decimal('discount_percentage', 5, 2);
            $table->decimal('maximum_discount', 10, 2);
            $table->date('start_date');
            $table->date('end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->decimal('discount_amount', 10, 2);
            $table->dropColumn('discount_percentage');
            $table->dropColumn('maximum_discount');
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
        });
    }
};
