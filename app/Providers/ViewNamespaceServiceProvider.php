<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class ViewNamespaceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Force add namespace for virtual views
        $path = str_replace('\\','/',realpath(resource_path('stubs/virtual/views')));
        Log::info('Virtual view namespace registered', ['path' => $path]);

        if(is_dir($path)){
            View::addNamespace('virtual', $path);
            Log::info('Namespace actualy added',['exist'=>file_exists($path.'/index.blade.php')]);
        }
        else{
            Log::warning('Virtual view path not found', ['path'=>$path]);
        }
    }

    public function boot(): void
    {
        
    }
}
