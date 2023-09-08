<?php

use App\Models\Account;
use App\Models\User;
use App\Services\Importers\ImportCostsService;
use Illuminate\Support\Collection;
use function Pest\Laravel\actingAs;
use function PHPUnit\Framework\assertTrue;

it('Imports costs', function (Collection $readCosts) {
    $account = Account::factory()->create();
    $user = User::factory()->create([
        'account_id' => $account->id,
    ]);
    actingAs($user);
    $costTemplateExcelReader = Mockery::mock('overload:App\Services\Readers\CostsTemplateExcelReader');
    $costTemplateExcelReader->shouldReceive('handle')->once()->andReturn($readCosts);

    $result = (new ImportCostsService())->handle('file', $account);

    assertTrue($result['provider']['created'] === 1);
    assertTrue($result['measurementType']['created'] === 1);
    assertTrue($result['ingredient']['created'] === 1);
    assertTrue($result['ingredientCost']['created'] === 1);

})->with('read_ingredient_costs_data');
