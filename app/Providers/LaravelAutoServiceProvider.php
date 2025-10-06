<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Console\Commands\CrudCommand;

class LaravelAutoServiceProvider extends ServiceProvider{
    public function register(){
        $this -> commands([
            crudCommand::class,
        ]);
    }
    public function boot(){
        
    }
}