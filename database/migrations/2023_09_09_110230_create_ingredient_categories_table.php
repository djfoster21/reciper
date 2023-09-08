<?php

use App\Models\Account;
use App\Models\IngredientCategory;
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
        Schema::create('ingredient_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Account::class);
            $table->string('name');
            $table->timestamps();
        });
        Schema::table('ingredients', function (Blueprint $table) {
            $table->foreignIdFor(IngredientCategory::class)->after('account_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredient_categories');
        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropColumn('ingredient_category_id');
        });
    }
};
