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
    }
    //automate migration skeleton
    public function createMigration(){
        $stub=$this->getStub('migration.stub');
        $migrationContent=str_replace('{{tableName}}', strtolower($this->name), $stub);
        $this->saveFile("database/migrations/" .date('Y_m_d_His'). "_create_".strtolower($this->name)."s_table.php", $migrationContent);
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
            $this->saveFile("resources/views/{$modelName}/{$view}.blade.php", $viewContent);
        }
    }
    protected function getStub($file){
        return File::get(resource_path("stubs/{$file}"));
    }
    protected function saveFile($path, $content){
        File::ensureDirectoryExists(dirname(base_path($path)));
        File::put(base_path($path), $content);
    }
}