<?php

use App\Models\Account;
use App\Models\Ingredient;
use App\Models\MeasurementType;
use App\Models\Provider;
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
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Account::class)->constrained();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('ingredient_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Account::class)->constrained();
            $table->foreignIdFor(Ingredient::class);
            $table->foreignIdFor(MeasurementType::class);
            $table->foreignIdFor(Provider::class);
            $table->integer('quantity');
            $table->integer('price');
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredient_costs');
        Schema::dropIfExists('providers');
    }
};
