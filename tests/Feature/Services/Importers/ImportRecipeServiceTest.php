<?php

use App\Models\Account;
use App\Models\User;
use App\Services\Importers\ImportRecipeService;
use App\Services\Readers\TemplateExcelRecipeReader;
use Illuminate\Support\Collection;

use function Pest\Laravel\actingAs;
use function PHPUnit\Framework\assertTrue;

it('Imports recipes', function (Collection $readRecipes) {
    $account = Account::factory()->create();
    $user = User::factory()->create([
        'account_id' => $account->id,
    ]);
    actingAs($user);
    $recipeTemplateExcelReader = Mockery::mock('overload:'.TemplateExcelRecipeReader::class);
    $recipeTemplateExcelReader->shouldReceive('handle')->once()->andReturn($readRecipes);

    $result = (new ImportRecipeService())->handle('file', $account);

    assertTrue($result['recipe']['created'] === 1);
    assertTrue($result['recipeCategory']['created'] === 1);
    assertTrue($result['recipeIngredient']['created'] === 2);
    assertTrue($result['ingredient']['created'] === 2);

})->with('read_recipe_data_one_recipe');
