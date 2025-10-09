<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;

class AutoController extends Controller
{
public function runAuto(Request $request)
{
    
    Artisan::call('migrate');
    Artisan::call("auto:crud Event"); //temp hardcode dynamic for model Event
    Artisan::call('db:seed');
    
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

    Log::info('OpenAPI generation output: ' . $process->getOutput());

    return response()->json(['status' => 'done']);
}
}

