<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class FileCreator{
    protected $name;
    public function __construct($name){
        $this->name = $name;
    }
    public function createModel(){
        $modelName = $this->name;
        $table = strtolower(Str::plural($modelName));
        $columns = $this->getSchemaDetails($table);
        $fillable=$this->generateFillable($columns);
        $swagger= $this->generateSwaggerAnnotations($columns);
        $modelStub=$this->getStub('Model.stub');
        $modelContent = str_replace(['{{modelName}}', '{{fillable}}', '{{requiredFields}}', '{{swaggerProperties}}'], [$modelName, $fillable, $swagger['swaggerRequired'], $swagger['swaggerProperties']],$modelStub);
        $this->saveFile("app/Models/{$modelName}.php", $modelContent);
    }

    //read model name from the seeder
    public function detectModelNameFromSeeder($fileName){
        return Str::replaceLast('Seeder.php', '', $fileName);
    }
    public function detectModelNameFromMigration($fileName){
        $table = Str::between($fileName, 'create_','_table');
        return Str::studly(Str::singular($table));
    }
    public function getSchemaDetails($table){
        return DB::select("SHOW COLUMNS FROM {$table}");
    }
    public function generateFillable($columns){
        return implode(', ', array_map(fn($col)=>"'{$col->Field}'",$columns));
    }
    public function generateValidationRules($columns){
        $rules =[];
        foreach ($columns as $col){
            $type =$col->Type;
            $nullable = $col->Null ==='YES';
            $name = $col->Field;

        if (Str::startsWith($type, 'varchar')){
            preg_match('/varchar\((\d+)\)/', $type, $match);
            $max = $match[1]?? 255;
            $rules[$name] = ($nullable ? 'nullable' :'required') . "|string|max:$max";
        }    
        elseif(Str::startsWith($type, 'int')){
            $rules[$name]=($nullable ? 'nullable' : 'required') . '|integer';
        }
        }
        return implode(",\n", array_map(fn($k, $v)=>"'$k'=>'$v'", array_keys($rules), $rules));
    }
    public function generateSwaggerAnnotations($columns){
        $required =[];
        $properties=[];

        foreach ($columns as $col){
            $name=$col->Field;
            $type=$col->Type;
            $nullable=$col->Null==='YES';

            if (!$nullable && $name!=='id'){
                $required[]="\"$name\"";
            }
            if (Str::startsWith($type, 'varchar')||Str::startsWith($type,'text')){
                $properties[]="new OA\\Property(property: \"$name\", type:\"string\", example: \"Example\")";
            }
            elseif(Str::startsWith($type, 'int')){
                $properties[]="new OA\\Property(property: \"$name\", type:\"integer\", example: 123)"; 
            }
            elseif(Str::startsWith($type, 'date')){
                $properties[]="new OA\\Property(property: \"$name\", type:\"string\", format:\"date\", example: \"2025-01-01\")";
            }
        }
        return ['swaggerRequired' =>implode(', ', $required),
                'swaggerProperties'=> implode (', ', $properties)];
    }
    public function generateFormFields($columns, $mode = 'create', $modelNameLower = ''){
        $fields=[];
        foreach ($columns as $col){
            $name=$col->Field;
            $type=$col->Type;
            $nullable=$col->Null==='YES';
            if (in_array($name, ['id', 'created_at', 'updated_at'])){
                continue;
            }
            $inputType= 'text';
            if (Str::startsWith($type, 'int')){
                $inputType ='number';
            }
            elseif(Str::startsWith($type,'date')){
                $inputType ='date';
            }
             $valueAttr = '';
            if ($mode === 'edit') {
                $valueAttr = "value=\"{{ \${$modelNameLower}->{$name} }}\"";
            }

             $fields[] = <<<HTML
<div class="mb-4">
  <label for="input{$name}" class="block text-sm font-medium text-gray-700 mb-1">{$name}:</label>
  <input
    type="{$inputType}"
    name="{$name}"
    id="input{$name}"
    placeholder="{$name}"
    {$valueAttr}
    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 @error('{$name}') border-red-500 @enderror"
  >
  @error('{$name}')
    <p class="text-sm text-red-600 mt-1">{{ \$message }}</p>
  @enderror
</div>
HTML;
    }

    return implode("\n", $fields);
}

    public function generateTableHeaders($columns){
        $headers=[];
        foreach ($columns as $col){
            $name =$col ->Field;
            if (in_array($name, ['id']))
                continue;
            $headers[] = "<th class =\"border px-4 py-2 text-center text-sm font-medium text-gray-700\">" . ucfirst($name) . "</th>";
        }
        return implode("\n", $headers);
    }
    public function generateTableRows($columns, $modelNameLower){
        $rows=[];
        foreach($columns as $col){
            $name =$col->Field;
            if (in_array($name, ['id']))
                continue;
            $rows[]="<td class=\"border px-4 py-2 text-sm text-gray-800\">
            {{ \${$modelNameLower}->{$name} }}
             </td>";
        }
        return implode("\n", $rows);
    }
    public function generateShowFields($columns,$modelNameLower){
        $fields = [];
        foreach ($columns as $col){
            $name = $col->Field;
            if (in_array($name, ['id']))
                continue;
            $fields[] =<<<HTML
            <div class="form-group">
                <strong>{$name}:</strong>
                {{ \${$modelNameLower}->{$name} }}
            </div>
            HTML;
        }
        return implode("\n", $fields);
    }
    
    /* temp comment out to test the read based on the existing migration and seed

    //automate migration skeleton
    public function createMigration(){
        $stub = $this->getStub('Migration.stub');
        $tableName = strtolower(Str::plural($this->name));
        $migrationContent = str_replace(['{{tableName}}'], [$tableName], $stub);
        $this->saveFile("database/migrations/" . date('Y_m_d_His') . "_create_{$tableName}_table.php", $migrationContent);
    }
    */

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
        $table=strtolower(Str::plural($modelName));
        $columns=$this->getSchemaDetails($table);
        $validationRules = $this->generateValidationRules($columns);        $swagger=$this->generateSwaggerAnnotations($columns);
        $controllerApiContent = str_replace(
            ['{{modelName}}', '{{modelNameLower}}', '{{modelNamePluralLower}}', '{{swaggerRequired}}', '{{swaggerProperties}}', '{{validationRules}}'],
            [$modelName, $modelNameLower, $modelNamePluralLower, $swagger['swaggerRequired'], $swagger['swaggerProperties'], $validationRules],
            $stub
        );
        $this->saveFile("app/Http/Controllers/Api/{$this->name}ApiController.php", $controllerApiContent);
    }
    public function createViews(){
        $views = ['index', 'show', 'create', 'edit', 'layout'];
        $modelName = $this->name;
        $modelNameLower = strtolower($modelName);
        $table=strtolower(Str::plural($modelName));
        $columns=$this->getSchemaDetails($table);
        $formFields = $this->generateFormFields($columns, 'edit', $modelNameLower);
        $tableHeaders = $this->generateTableHeaders($columns);
        $tableRows = $this->generateTableRows($columns, $modelNameLower);
        $showFields = $this->generateShowFields($columns, $modelNameLower);

        foreach ($views as $view){
            $stub =$this->getStub("views/{$view}.stub");
            $viewContent=str_replace(['{{modelName}}', '{{modelNameLower}}'],[$modelName.'s', $modelNameLower], $stub);
            if ($view === 'create') {
    $formFields = $this->generateFormFields($columns, 'create', $modelNameLower);
    $viewContent = str_replace('{{formFields}}', $formFields, $viewContent);
}

if ($view === 'edit') {
    $formFields = $this->generateFormFields($columns, 'edit', $modelNameLower);
    $viewContent = str_replace('{{formFields}}', $formFields, $viewContent);
}
             if ($view === 'index') {
                $viewContent = str_replace(
                    ['{{tableHeaders}}', '{{tableRows}}'],
                    [$tableHeaders, $tableRows],
                    $viewContent
                );
            }

            if ($view === 'show') {
                $viewContent = str_replace('{{showFields}}', $showFields, $viewContent);
            }

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
    public function createDataSeeder(){
        $stub=$this->getStub('DataSeeder.stub'); 
        $modelName = $this->name;
        $dataSeederContent = str_replace(
            ['{{modelName}}'],
            [$modelName],
            $stub
        );
        $this->appendToFile("database/seeders/DatabaseSeeder.php", $dataSeederContent);
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
        return File::get(resource_path("stubs/legacy/{$file}"));
    }
    protected function saveFile($path, $content){
        File::ensureDirectoryExists(dirname(base_path($path)));
        File::put(base_path($path), $content);
    }
    protected function appendToFile($path, $content){
        File::append(base_path($path), "\n". $content);
    }
}