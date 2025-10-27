<?php

namespace App\Helpers;
\Log::info('FileCreator actually loaded from: ' . __FILE__);

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
   public function generateFormFields($columns, $mode = 'create', $modelNameLower = '', $row = null)
{
    $fields = [];

    foreach ($columns as $col) {
        $name = $col->Field;
        $type = $col->Type;
        $label = ucfirst(str_replace('_', ' ', $name));

        // Skip system fields
        if (in_array($name, ['id', 'created_at', 'updated_at'])) continue;

        // Determine input type
        $inputType = 'text';
        $isDate = false;
        $isCheckbox = false;
        $enableTime = false;

        if (Str::startsWith($type, 'int')) {
            $inputType = 'number';
        } elseif (Str::startsWith($type, ['tinyint(1)', 'boolean'])) {
            $inputType = 'checkbox';
            $isCheckbox = true;
        } elseif (Str::startsWith($type, ['date', 'datetime', 'timestamp'])) {
            $isDate = true;
            $enableTime = Str::contains($type, ['datetime', 'timestamp']);
        }

        // Value for edit mode
        $value = ($mode === 'edit' && $row) ? ($row->$name ?? '') : '';
        $safeValue = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

        if ($mode === 'edit'){
            $safeValue = "{{ old('{$name}', \${$modelNameLower}->{$name}) }}";
        }else{
            $safeValue = "{{ old('{$name}') }}";
        }

        if (Str::contains($type, ['text', 'blob'])) {
            $fieldHTML = <<<HTML
            <div class="mb-4">
                <label for="{$name}" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">{$label}</label>
                <textarea
                    name="{$name}"
                    id="{$name}"
                    placeholder="{$label}"
                    rows="4"
                    class="w-full bg-gray-400 dark:bg-gray-700 text-gray-700 dark:text-gray-400 border border-gray-700 rounded-lg px-3 py-2 
                           focus:outline-none focus:ring-2 focus:ring-blue-500 
                           @error('{$name}') border-red-500 @enderror"
                >{$safeValue}</textarea>
            </div>
            HTML;
        }
        elseif ($isCheckbox) {
            $checked = ($value == 1 || $value === true) ? 'checked' : '';
            $fieldHTML = <<<HTML
            <div class="mb-4 flex items-center">
                <input
                    type="checkbox"
                    name="{$name}"
                    id="{$name}"
                    {$checked}
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-600 rounded bg-gray-700"
                >
                <label for="{$name}" class="ml-2 text-sm font-medium text-gray-700">{$label}</label>
            </div>
            HTML;
        }
        
        //DATE / DATETIME (Flatpickr-enabled)
        elseif ($isDate) {
            $timeAttr = $enableTime ? 'data-enable-time="true"' : '';
            $fieldHTML = <<<HTML
            <div class="mb-4">
                <label for="{$name}" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">{$label}</label>
                <div class="relative">
                    <input
                        type="text"
                        name="{$name}"
                        id="{$name}"
                        value="{$safeValue}"
                        placeholder="Select {$label}"
                        class="datepicker w-full bg-gray-400 dark:bg-gray-700 text-gray-700 dark:text-gray-400 border border-gray-700 rounded-lg px-3 py-2 pr-10 
                               focus:outline-none focus:ring-2 focus:ring-blue-500 
                               @error('{$name}') border-red-500 @enderror"
                        autocomplete="off"
                        data-fp-init="false"
                        {$timeAttr}
                    >
                <!--
                    <button type="button" 
                            class="datepicker-toggle absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-300 z-10"
                            data-target="#{$name}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zM4 8h12v6H4V8z"/>
                        </svg>
                    </button>
                -->
                </div>
            </div>
            HTML;
        }

        //DEFAULT INPUT
        else {
            $fieldHTML = <<<HTML
            <div class="mb-4">
                <label for="{$name}" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">{$label}</label>
                <input
                    type="{$inputType}"
                    name="{$name}"
                    id="{$name}"
                    value="{$safeValue}"
                    placeholder="Enter {$label}"
                    class="w-full bg-gray-400 dark:bg-gray-700 text-gray-700 dark:text-gray-400 border border-gray-700 rounded-lg px-3 py-2 
                           focus:outline-none focus:ring-2 focus:ring-blue-500 
                           @error('{$name}') border-red-500 @enderror"
                >
            </div>
            HTML;
        }

        $fields[] = $fieldHTML;
    }

    // Combine
    $out = implode("\n", $fields);

    try {
        $debugPath = storage_path("debug_virtual_" . ($modelNameLower ?: 'form') . ".html");
        file_put_contents($debugPath, $out);
    } catch (\Throwable $e) {
        Log::warning('VirCreator: failed to write debug form file', ['err' => $e->getMessage()]);
    }

    return $out;
}
    public function generateTableHeaders($columns){
        $headers=[];
        foreach ($columns as $col){
            $name =$col ->Field;
            if (in_array($name, ['id']))
                continue;
            $headers[] = "<th class =\"px-4 py-2 text-center text-xs text-sm font-medium text-gray-700 dark:text-gray-300\">" . ucfirst($name) . "</th>";
        }
        return implode("\n", $headers);
    }
    public function generateTableRows($columns, $modelNameLower){
        $rows=[];
        foreach($columns as $col){
            $name =$col->Field;
            if (in_array($name, ['id']))
                continue;
            $rows[]="<td class=\" px-4 py-2 text-sm text-gray-700 dark:text-gray-300\">
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
    public function createWebRoute()
{
    $stub = $this->getStub('WebRoute.stub'); 
    $modelName = $this->name;
    $modelNameLower = strtolower($modelName);
    $modelNamePluralLower = strtolower(Str::plural($modelName));

    $webRouteContent = str_replace(
        ['{{modelName}}', '{{modelNameLower}}', '{{modelNamePluralLower}}'],
        [$modelName, $modelNameLower, $modelNamePluralLower],
        $stub
    );

    $routesPath = base_path('routes/web.php');

    try {
        $existing = file_exists($routesPath) ? file_get_contents($routesPath) : '';

        // Only skip if this exact route already exists
        if (strpos($existing, "Route::resource('{$modelNameLower}'") !== false) {
            \Log::info("⚠️ Route for {$modelNameLower} already exists. Skipping append.");
            return;
        }

        $this->appendToFile('routes/web.php', $webRouteContent);
        \Log::info("✅ Route appended for {$modelNameLower}");

    } catch (\Throwable $e) {
        \Log::error("❌ Failed to append route for {$modelNameLower}: " . $e->getMessage());
    }
}

protected function appendToFile($path, $content)
{
    $fullPath = base_path($path);

    // Ensure newline before appending
    $existing = file_exists($fullPath) ? file_get_contents($fullPath) : '';
    if (!str_ends_with(trim($existing), PHP_EOL)) {
        $existing .= PHP_EOL;
    }

    $finalContent = $existing . PHP_EOL . trim($content) . PHP_EOL;
    File::put($fullPath, $finalContent);
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
}