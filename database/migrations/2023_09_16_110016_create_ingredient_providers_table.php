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
        Schema::create('ingredient_providers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Account::class)->constrained();
            $table->foreignIdFor(\App\Models\Ingredient::class);
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredient_providers');
    }
};
