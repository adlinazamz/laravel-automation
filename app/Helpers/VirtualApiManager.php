<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class VirtualApiManager
{
    protected static string $apiStub = 'resources/stubs/virtual/ApiRoute.stub';
    protected static string $controllerStub = 'resources/stubs/virtual/ApiController.stub';
    protected static string $controllerPath = 'app/Http/Controllers/Api/VirtualApiController.php';
    protected static string $apiRoutesPath = 'routes/api.php';

    /**
     * Register a virtual table in Swagger documentation.
     */
    public static function registerVirtualTable(string $table)
{
    Log::info("[VirtualApiManager] Registering virtual table: {$table}");

    // 1. Build metadata
    $metadata = \App\Helpers\VirCreator::virtualModel($table);

    // 2. Render controller stub for Swagger scanning
    $stubContent = \App\Helpers\VirCreator::renderFromStub(
        'ApiController.stub',
        [
            'table' => $table,
            'modelName' => ucfirst($table),
            'modelNameLower' => strtolower($table),
            'swaggerProperties' => self::generateSwaggerProperties($metadata['fields']),
            'swaggerRequired' => self::generateSwaggerRequired($metadata['fields']),
            'validationRules' => self::generateValidationRules($metadata['fields']),
        ]
    );

    // 3. Store virtual OA stub
    $tempPath = base_path('storage/virtual_oa');
    File::ensureDirectoryExists($tempPath);
    File::put("{$tempPath}/{$table}.php", $stubContent);
    Log::info("[VirtualApiManager] OA stub generated for {$table}");

    // 4. Append per-table route (using anchor)
   

    $routePath = base_path('routes/api.php');
    $contents  = File::get($routePath);

    if (!str_contains($contents, "/virtual/{$table}")) {
        // Match the anchor comment regardless of spacing
        $updated = preg_replace(
            '/\/\/\s*virtual routes will be appended below automatically\s*/i',
            "// virtual routes will be appended below automatically\n    " . trim($routeStub) . "\n",
            $contents
        );

        if ($updated !== null) {
            File::put($routePath, $updated);
            Log::info("[VirtualApiManager] Route appended for virtual table: {$table}");
        } else {
            Log::warning("[VirtualApiManager] Regex failed when adding route for {$table}");
        }
    } else {
        Log::info("[VirtualApiManager] Route for {$table} already exists, skipping.");
    }

    // 5. Regenerate Swagger
    self::regenerateSwagger();
}


    /**
     * Generate Swagger @OA\Property annotations for fields.
     */
    protected static function generateSwaggerProperties(array $fields): string
    {
        $props = [];

        foreach ($fields as $name => $meta) {
            $type = match (true) {
                str_contains($meta['type'], 'int') => 'integer',
                str_contains($meta['type'], 'bool') => 'boolean',
                str_contains($meta['type'], 'text') => 'string',
                default => 'string',
            };

            $props[] = <<<OA
new \\OpenApi\\Attributes\\Property(property="$name", type="$type")
OA;
        }

        return implode(",\n", $props);
    }

    /**
     * Ensure controller and routes are set up.
     */
    public static function ensureApiIsReady(): void
    {
        Log::info('[VirtualApiManager] Starting ensureApiIsReady');
        self::ensureController();
        self::ensureRoutes();
        self::regenerateSwagger();
        self::ensureDynamicVirtualRoutes(); // <-- add this line

    }

    protected static function ensureController(): void
    {
        $target = base_path(self::$controllerPath);

        if (!File::exists($target)) {
            $stub = base_path(self::$controllerStub);
            if (!File::exists($stub)) {
                Log::error('[VirtualApiManager] Missing ApiController.stub');
                return;
            }

            File::ensureDirectoryExists(dirname($target));
            File::copy($stub, $target);
            Log::info('[VirtualApiManager] VirtualApiController generated.');
        } else {
            Log::info('[VirtualApiManager] VirtualApiController already exists.');
        }
    }

   protected static function ensureRoutes(): void
{
    $routePath = base_path(self::$apiRoutesPath);
    $contents = File::get($routePath);

}
protected static function ensureDynamicVirtualRoutes(): void
{
    $routePath = base_path(self::$apiRoutesPath);
    $contents = File::get($routePath);

    // Add only once — if not already there
    if (!str_contains($contents, "Route::prefix('api/virtual'")) {
        $dynamicBlock = <<<ROUTE

// == Global Virtual API Dynamic Route ==
Route::prefix('api/virtual')->middleware('auth:api')->group(function () {
    Route::get('/{table}', [App\Http\Controllers\Api\VirtualApiController::class, 'index']);
    Route::post('/{table}', [App\Http\Controllers\Api\VirtualApiController::class, 'store']);
    Route::get('/{table}/{id}', [App\Http\Controllers\Api\VirtualApiController::class, 'show']);
    Route::put('/{table}/{id}', [App\Http\Controllers\Api\VirtualApiController::class, 'update']);
    Route::delete('/{table}/{id}', [App\Http\Controllers\Api\VirtualApiController::class, 'destroy']);
});
ROUTE;

        File::append($routePath, PHP_EOL . $dynamicBlock);
        Log::info('[VirtualApiManager] Global dynamic virtual route block added.');
    } else {
        Log::info('[VirtualApiManager] Global dynamic virtual route already exists.');
    }
}
    /**
     * Regenerate Swagger documentation including virtual stubs.
     */
    protected static function regenerateSwagger(): void
    {
        try {
            $outputFile = base_path('storage/api-docs.json');
            $scanPaths = implode(' ', [
                escapeshellarg(base_path('app')),
                escapeshellarg(base_path('storage/virtual_oa')),
            ]);

            $cmd = 'php vendor/bin/openapi --output ' . escapeshellarg($outputFile) . ' ' . $scanPaths;
            shell_exec($cmd);

            Log::info('[VirtualApiManager] Swagger documentation regenerated.');
        } catch (\Throwable $e) {
            Log::error('[VirtualApiManager] OpenAPI generation failed: ' . $e->getMessage());
        }
    }
}
