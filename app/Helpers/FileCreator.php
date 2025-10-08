<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\facades\File;

class FileCreator{
    protected $name;
    public function __construct($name){
        $this->name = $name;
    }
    public function createModel(){
        $stub=$this->getStub('Model.stub');
        $modelName = $this->name;
        $modelContent = str_replace('{{modelName}}', $modelName,$stub);
        $this->saveFile("app/Models/{$modelName}.php", $modelContent);
    }

    //automate migration skeleton
    public function createMigration(){
        $stub = $this->getStub('Migration.stub');
        $tableName = strtolower(Str::plural($this->name));
        $migrationContent = str_replace(['{{tableName}}'], [$tableName], $stub);
        $this->saveFile("database/migrations/" . date('Y_m_d_His') . "_create_{$tableName}_table.php", $migrationContent);
    }
    
    public function createController(){
        $stub=$this->getStub('Controller.stub');
        $modelName = $this->name;
        $modelNameLower = strtolower($modelName);
        $modelNamePluralLower = strtolower(Str::plural($modelName));
        $controllerContent = str_replace(
            ['{{modelName}}', '{{modelNameLower}}', '{{modelNamePluralLower}}'],
            [$modelName, $modelNameLower, $modelNamePluralLower],
            $stub
        );
        $this->saveFile("app/Http/Controllers/{$modelName}Controller.php", $controllerContent);
    }

    public function createApiController(){
        $stub=$this->getStub('ApiController.stub'); 
        $modelName = $this->name;
        $modelNameLower = strtolower($modelName);
        $modelNamePluralLower = strtolower(Str::plural($modelName));
        $controllerApiContent = str_replace(
            ['{{modelName}}', '{{modelNameLower}}', '{{modelNamePluralLower}}'],
            [$modelName, $modelNameLower, $modelNamePluralLower],
            $stub
        );
        $this->saveFile("app/Http/Controllers/Api/{$this->name}ApiController.php", $controllerApiContent);
    }
    public function createViews(){
        $views = ['index', 'show', 'create', 'edit', 'layout'];
        $modelName = $this->name;
        $modelNameLower = strtolower($modelName);
        foreach ($views as $view){
            $stub =$this->getStub("views/{$view}.stub");
            $viewContent=str_replace(['{{modelName}}', '{{modelNameLower}}'],[$modelName.'s', $modelNameLower], $stub);
            $this->saveFile("resources/views/{$modelNameLower}/{$view}.blade.php", $viewContent);
        }
    }
    public function createWebRoute(){
        $stub=$this->getStub('WebRoute.stub'); 
        $modelName = $this->name;
        $modelNameLower = strtolower($modelName);
        $modelNamePluralLower = strtolower(Str::plural($modelName));
        $webRouteContent = str_replace(
            ['{{modelName}}', '{{modelNameLower}}', '{{modelNamePluralLower}}'],
            [$modelName, $modelNameLower, $modelNamePluralLower],
            $stub
        );
        $this->appendToFile("routes/web.php", $webRouteContent);
    }
    public function createApiRoute(){
        $stub=$this->getStub('ApiRoute.stub'); 
        $modelName = $this->name;
        $modelNameLower = strtolower($modelName);
        $modelNamePluralLower = strtolower(Str::plural($modelName));
        $apiRouteContent = str_replace(
            ['{{modelName}}', '{{modelNameLower}}', '{{modelNamePluralLower}}'],
            [$modelName, $modelNameLower, $modelNamePluralLower],
            $stub
        );
        $this->appendToFile("routes/api.php", $apiRouteContent);
    }
    //adding to sidebar
    public function createSideNav(){
        $stub=$this->getStub('SideNav.stub'); 
        $modelName = $this->name;
        $modelNameLower = strtolower($modelName);
        $modelNamePluralLower = strtolower(Str::plural($modelName));
        $sideNavContent = str_replace(
            ['{{modelName}}', '{{modelNameLower}}', '{{modelNamePluralLower}}'],
            [$modelName, $modelNameLower, $modelNamePluralLower],
            $stub
        );
        $this->appendToFile("resources/views/layouts/sidenav-link.blade.php", $sideNavContent);
    }

    protected function getStub($file){
        return File::get(resource_path("stubs/{$file}"));
    }
    protected function saveFile($path, $content){
        File::ensureDirectoryExists(dirname(base_path($path)));
        File::put(base_path($path), $content);
    }
    protected function appendToFile($path, $content){
        File::append(base_path($path), "\n". $content);
    }
}