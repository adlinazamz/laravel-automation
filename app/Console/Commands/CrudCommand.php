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
        $creator->createApiController();//for api controller
        $creator->createWebRoute(); //update and inject new web route for the item into existing web.php
        $creator->createApiRoute(); //update and inject new api route for the item into existing api.php

        $this->info("CRUD for $name created successfully.");
    }
}