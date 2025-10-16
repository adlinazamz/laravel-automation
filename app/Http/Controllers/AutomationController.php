<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class AutomationController extends Controller
{
   public function listTables()
{
    $dbName = DB::getDatabaseName();

    $tables = DB::select("
        SELECT TABLE_NAME 
        FROM information_schema.tables 
        WHERE table_schema = ? 
        AND table_type = 'BASE TABLE'
    ", [$dbName]);

    $tableNames = array_map(fn($table) => $table->TABLE_NAME, $tables);

    return response()->json(['tables' => $tableNames]);
}
public function runAutomation(Request $request)
{
    
    Log::info('Automation Virtual CRUD trigerred', ['input'=>$request->all()]);

    $table = $request->input('table');
    if (!$table) {
        return response()->json(['status' => 'error', 'message' => 'No table selected'], 400);
    }
 $model = ucfirst(Str::camel(Str::singular($table)));

    Artisan::call('migrate');
    Artisan::call("virtual:crud {$table}");
    //Artisan::call('db:seed');
    /*$seederPath = database_path("seeders/{$model}Seeder.php");
    if (File::exists($seederPath)) {
        Artisan::call("db:seed", ['--class' => "{$model}Seeder"]);
    }*/

    $phpPath='C:\\laragon\\bin\\php\\php-8.1.10-Win32-vs16-x64\\php.exe';

    $process = new Process([
        $phpPath,
        base_path('vendor/zircote/swagger-php/bin/openapi'),
        '--bootstrap', base_path('vendor/autoload.php'),
        base_path('app'),
        '-o', base_path('public/swagger.json')
    ]);
    $process->setWorkingDirectory(base_path());
    $process->run();

    if (!$process->isSuccessful()) {
        Log::error('OpenAPI generation failed: ' . $process->getErrorOutput());
        return response()->json(['status' => 'error', 'message' => $process->getErrorOutput()], 500);
    }
 Log::info('Automation Controller: virtual CRUD completed');

    return response()->json(['status' => 'done']);
}
}

