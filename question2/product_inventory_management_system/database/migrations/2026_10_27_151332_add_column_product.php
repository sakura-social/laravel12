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
        //Alter table 'products' to add a new column 'soft_delete' with integer type and default value of 0
        Schema::table('products', function (Blueprint $table) {
            $table->tinyInteger('soft_delete')->default(0);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
