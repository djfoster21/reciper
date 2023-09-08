<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\Importers\ImportCostsService;
use App\Services\Importers\ImportRecipeService;
use App\Services\Importers\ImportServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ImportController extends Controller
{
    public function recipes(Request $request, ImportRecipeService $importRecipeService): JsonResponse
    {
        return $this->getUploadedFile($request, $importRecipeService);
    }

    public function costs(Request $request, ImportCostsService $importCostsService): JsonResponse
    {
        return $this->getUploadedFile($request, $importCostsService);
    }

    protected function getUploadedFile(Request $request, ImportServiceInterface $importCostsService): JsonResponse
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $uploadedFile = $validated['file'];
        try {
            $file = $uploadedFile->getRealPath();
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'message' => 'Could not read file',
            ], 400);

        }
        $costs = $importCostsService->handle($file, $request->user()->account);

        return new JsonResponse($costs);
    }
}
