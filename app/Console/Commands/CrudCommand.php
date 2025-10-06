<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use App\Helpers\FileCreator;

class CrudCommand extends Command{
    protected $signature ='auto:crud {name}';
    protected $description ='Generate model, migration, controller and views for the given model';
    
    public function handle(){
        $name =ucfirst($this-> argument('name'));
        $creator = new FileCreator($name);

        $this->info("Generating CRUD for model: $name");

        //File generating
        $creator->createModel();
        $creator->createMigration();
        $creator->createController();
        $creator->createViews();

        $this->info("CRUD for $name created successfully.");
    }
}