<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;

class AutomationController extends Controller
{
    public function runAutomation(Request $request){
        $modelName = $request -> input('model');
        Artisan::call("auto:crud {$modelName}");
        Artisan::call('migrate');   
        
        $process =new Process([
            './vendor/bin/openapi',
            '--bootstrap','vendor/autoload.php',
            'app',
            '-o', 'public/swagger.json'
        ]);
        $process->setWorkingDirectory(base_path());
        $process->run();
        return response()->json(['status' => 'done']);
    }
}

