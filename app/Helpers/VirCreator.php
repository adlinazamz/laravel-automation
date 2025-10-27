<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


/**
 * VirCreator (Virtual Edition)
 * --------------------------------------
 * This refactored version removes physical file generation and instead provides
 * a virtualized CRUD + template rendering layer. It dynamically builds forms,
 * tables, and model metadata from the database schema. You can also reuse stub
 * templates for consistent structure without saving to disk.
 *
 * Usage Example:
 *   VirCreator::handle('cars', 'index');
 *   VirCreator::renderFromStub('Controller.stub', ['modelName' => 'Car']);
 */
class VirCreator
{
    /**
     * Retrieve schema details for a given table.
     * Portable alternative to SHOW COLUMNS for cross-database support.
     */

    public static function getSchema(string $table): array
    {
        Log::info('VirCreator: started', ['table'=> $table]);

        try {
            return array_map(fn($col) => (object)['Field' => $col, 'Type' => 'string', 'Null' => 'YES'],
                DB::getSchemaBuilder()->getColumnListing($table));
        } catch (\Throwable $e) {
            // Fallback to MySQL-specific query if schema builder fails
            return DB::select("SHOW COLUMNS FROM {$table}");
        }
    }

    /**
     * Virtual model representation: returns table name and fields with metadata.
     */
    public static function virtualModel(string $table): array
    {
        Log::info('VirCreator: register routes and build metedata');

        $columns = self::getSchema($table);
        $fields = [];
        foreach ($columns as $col) {
            $fields[$col->Field] = [
                'type' => $col->Type ?? 'string',
                'nullable' => ($col->Null ?? 'YES') === 'YES',
                'key' => $col->Key ?? '',
            ];
        }
        return [
            'table' => $table,
            'fields' => $fields,
        ];
    }
    public function generateShowFields($columns,$modelNameLower, $row){
        $fields = [];
        foreach ($columns as $col){
            $name = $col->Field;
            if (in_array($name, ['id']))
                continue;
            $value = $row ->$name ?? '';
            $fields[] = <<<HTML
                <div class="mb-2">
                    <strong class="capitalize">{$name}:</strong>
                    {$value}
                </div>
            HTML;
        }
        return implode("\n", $fields);
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
    /**
     * Virtual generic CRUD handler.
     */
    public static function handle(string $table, string $action, ?int $id = null, ?array $payload = null)
    {
        Log::info('VirCreator: building CRUD handler');

        switch ($action) {
            case 'index':
                return DB::table($table)->paginate(10);

            case 'show':
                return DB::table($table)->find($id);

            case 'store':
                return DB::table($table)->insertGetId($payload ?? []);

            case 'update':
                return DB::table($table)->where('id', $id)->update($payload ?? []);

            case 'destroy':
            case'delete':
                return DB::table($table)->where('id', $id)->delete();

            default:
                throw new \InvalidArgumentException("Unsupported action: {$action}");
        }
        Log::info('VirCreator: completed CRUD handler', ['status'=> 'ok']);

    }

    /**
     * Normalize date-like fields in a payload to YYYY-MM-DD before storing.
     * Accepts common input formats and attempts strict parsing first.
     */
    public static function normalizeDates(string $table, array $payload): array
    {
        $columns = self::getSchema($table);
        $dateFields = array_filter($columns, fn($c) => Str::startsWith($c->Type ?? '', 'date'));

        if (empty($dateFields)) return $payload;

        $formats = ['d-m-Y','d/m/Y','Y-m-d','Y/m/d','d.m.Y','d M Y','D-M-YY'];

        foreach ($dateFields as $col) {
            $name = $col->Field;
            if (!array_key_exists($name, $payload)) continue;
            $val = trim((string)($payload[$name] ?? ''));
            if ($val === '') continue;

            $normalized = null;
            // try common formats first (accept parse even if string formatting differs)
            foreach ($formats as $fmt) {
                try {
                    $dt = Carbon::createFromFormat($fmt, $val);
                    if ($dt) {
                        $normalized = $dt->format('Y-m-d');
                        break;
                    }
                } catch (\Exception $e) {
                    // continue
                }
            }

            if (!$normalized) {
                // try loose parse
                try {
                    $dt = Carbon::parse($val);
                    $normalized = $dt->format('Y-m-d');
                } catch (\Exception $e) {
                    Log::warning('VirCreator: failed to parse date field', ['field' => $name, 'value' => $val]);
                }
            }

            if ($normalized) {
                $payload[$name] = $normalized;
            }
        }

        return $payload;
    }

    /**
     * Render HTML directly from a registered template name.
     */
    public static function renderTemplate(string $table, string $template): string
    {
        Log::info('VirCreator: rendering from template name');

        $columns = self::getSchema($table);
        $fields = array_map(fn($c) => $c->Field, $columns);

        $templates = [
            'form' => fn() => self::renderForm($table,$fields),
            'table' => fn() => self::renderTable($fields),
            'show' => fn() => self::renderShow($fields),
        ];

        if (!isset($templates[$template])) {
            throw new \InvalidArgumentException("Unknown template: {$template}");
        }

        $html =  $templates[$template]();
        Log::info('VirCreator: completed HTML render', ['status'=> 'ok']);
        return $html;

    }

    /**
     * Render template from stub file dynamically (without saving to disk).
     */
    public static function renderFromStub(string $stubFile, array $replacements = []): string
    {
        $path = resource_path("stubs/virtual/{$stubFile}");
        if (!file_exists($path)) {
            throw new \RuntimeException("Stub not found: {$stubFile}");
        }

        $stub = file_get_contents($path);
        foreach ($replacements as $key => $value) {
            $stub = str_replace('{{' . $key . '}}', $value, $stub);
        }
       
        return $stub;
    }

    /**
     * Render form fields.
     */
    protected static function renderForm(string $table, array $fields): string
{
    $html = '';

    // Build a quick map of column types so we can render date inputs when needed
    try {
        $schema = self::getSchema($table);
        $typeMap = [];
        foreach ($schema as $c) {
            $typeMap[$c->Field] = $c->Type ?? '';
        }
    } catch (\Throwable $e) {
        $typeMap = [];
    }

    foreach ($fields as $name) {
        if (in_array($name, ['id', 'created_at', 'updated_at'])) continue;
        $label = ucfirst($name);
        $colType = strtolower($typeMap[$name] ?? '');

        // Render a date input when the column type looks like date/datetime/timestamp
        if (str_starts_with($colType, 'date') || str_contains($colType, 'datetime') || str_contains($colType, 'timestamp')) {
            $html .= "<div class='mb-4'>\n";
            $html .= "  <label for='{$name}' class='block text-sm font-medium text-gray-200 mb-1'>{$label}</label>\n";
            $html .= "  <div class='relative'>\n";
            $html .= "    <input type='text' name='{$name}' id='{$name}' value='' placeholder='Select {$label}' class='datepicker w-full bg-gray-700 text-gray-100 border border-gray-600 rounded px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500' autocomplete='off' data-fp-init='false'>\n";
            $html .= "    <button type='button' class='datepicker-toggle absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-300' data-target='#{$name}'>\n";
            $html .= "      <svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' viewBox='0 0 20 20' fill='currentColor'>\n";
            $html .= "        <path d='M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zM4 8h12v6H4V8z'/>\n";
            $html .= "      </svg>\n";
            $html .= "    </button>\n";
            $html .= "  </div>\n";
            $html .= "</div>\n";
        } else {
            $html .= "<div class='mb-4'>\n";
            $html .= "  <label class='block mb-1 text-sm font-medium text-gray-200'>{$name}</label>\n";
            $html .= "  <input name='{$name}' class='w-full bg-gray-700 text-gray-100 border border-gray-600 rounded px-3 py-2' placeholder='{$name}'>\n";
            $html .= "</div>\n";
        }
    }

    Log::info('Rendered HTML length', ['table'=>$table, 'length'=>strlen($html)]);

    $filePath = storage_path("debug_{$table}.html");
    file_put_contents($filePath, $html);
    Log::info('HTML dumped to', ['path'=>$filePath]);

    return $html;
}

    /**
     * Render table headers and sample rows.
     */
    protected static function renderTable(array $fields): string
    {
        $headers = implode('', array_map(fn($f) => "<th class='border px-3 py-2'>" . ucfirst($f) . "</th>", $fields));
        $rows = implode('', array_map(fn($f) => "<td class='border px-3 py-2'>{{ \${$f} }}</td>", $fields));

        return <<<HTML
        <table class="table-auto w-full border-collapse border">
            <thead><tr>{$headers}</tr></thead>
            <tbody><tr>{$rows}</tr></tbody>
        </table>
        HTML;
    }

    /**
     * Render show/detail block.
     */
    protected static function renderShow(array $fields): string
    {
        $blocks = array_map(fn($f) => "<div><strong>{$f}:</strong> {{ \${$f} }}</div>", $fields);
        return implode("\n", $blocks);
    }
    
    /**
 * Add a virtual module link to the sidebar permanently.
 *
 * @param string $modelName
 */
public function createSideNav(string $modelName)
{
    $stub = self::getStub('SideNav.stub');

    $modelNameLower = strtolower($modelName);
    $modelNamePluralLower = strtolower(Str::plural($modelName));

    $sideNavContent = str_replace(
        ['{{modelName}}', '{{modelNameLower}}', '{{modelNamePluralLower}}'],
        [$modelName, $modelNameLower, $modelNamePluralLower],
        $stub
    );

    $sideNavPath = base_path("resources/views/layouts/sidenav-link.blade.php");

    // Ensure file exists
    if (!file_exists($sideNavPath)) {
        file_put_contents($sideNavPath, "<!-- Virtual SideNav -->\n");
    }

    $existing = file_get_contents($sideNavPath);

    // Normalize: remove spaces, tabs, newlines, and braces
    $existingNormalized = preg_replace('/[\s{}]+/', '', $existing);
    $checkString = "route('virtual.index',['table'=>'$modelNameLower'])";
    $checkStringNormalized = preg_replace('/[\s{}]+/', '', $checkString);

    if (str_contains($existingNormalized, $checkStringNormalized)) {
        Log::info("Virtual sidenav link for {$modelNameLower} already exists. Skipping.");
        return;
    }

    try {
        file_put_contents($sideNavPath, PHP_EOL . $sideNavContent, FILE_APPEND);
        Log::info("Virtual sidenav link added for {$modelNameLower}");
    } catch (\Throwable $e) {
        Log::error("Failed to add sidenav link for {$modelNameLower}: {$e->getMessage()}");
    }
}

/**
 * Render persistent web route for virtual CRUD.
 *
 * @param string $modelName
 */
public function createWebRoute(string $modelName)
{
    $stub = self::getStub('WebRoute.stub');

    $modelNameLower = strtolower($modelName);
    $modelNamePluralLower = strtolower(Str::plural($modelName));

    $webRouteContent = str_replace(
        ['{{modelName}}', '{{modelNameLower}}', '{{modelNamePluralLower}}'],
        [$modelName, $modelNameLower, $modelNamePluralLower],
        $stub
    );

    $webRoutePath = base_path("routes/web.php");

    // Ensure file exists
    if (!file_exists($webRoutePath)) {
        file_put_contents($webRoutePath, "<?php\n\n// Web Routes\n");
    }

    $existing = file_get_contents($webRoutePath);

    // Normalize
    $existingNormalized = preg_replace('/\s+/', '', $existing);
    $checkString = "Route::get('/{table}',[App\\Http\\Controllers\\VirtualController::class,'index'])->name('virtual.index')";
    $checkStringNormalized = preg_replace('/\s+/', '', $checkString);

    if (str_contains($existingNormalized, $checkStringNormalized)) {
        Log::info("Web route for {$modelNameLower} already exists. Skipping.");
        return;
    }

    try {
        $this->appendToFile("routes/web.php", $webRouteContent);
        Log::info("Web route added for {$modelNameLower}");
    } catch (\Throwable $e) {
        Log::error("Failed to add web route for {$modelNameLower}: {$e->getMessage()}");
    }
}
    protected function getStub($file){
        return File::get(resource_path("stubs/virtual/{$file}"));
    }
    protected function appendToFile($path, $content){
        File::append(base_path($path), "\n". $content);
    }
}
