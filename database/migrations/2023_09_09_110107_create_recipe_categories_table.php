<?php

use App\Models\Account;
use App\Models\RecipeCategory;
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
        Schema::create('recipe_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Account::class);
            $table->string('name');
            $table->timestamps();
        });
        Schema::table('recipes', function (Blueprint $table) {
            $table->foreignIdFor(RecipeCategory::class)->after('account_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_categories');
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropColumn('recipe_category_id');
        });
    }
};
