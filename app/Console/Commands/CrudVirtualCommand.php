<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\VirCreator;//virtual
use App\Helpers\VirtualApiManager; //manage api creation on virtual
use Illuminate\Support\Facades\Log;

class CrudVirtualCommand extends Command{
    protected $signature ='virtual:crud {table}{template=form}';
    protected $description = 'Render virtual CRUD HTML for a given table and template';
    
    public function handle()
{
    Log::info('CrudVirtualCommand: invoke');
    $table = strtolower($this->argument('table'));
    $template = $this->argument('template');
    $this->info("Rendering virtual CRUD for table: {$table} using template: {$template}");

    try {
        // Add to sidenav permanently
        $vir = new VirCreator();
        $vir->createSideNav($table);

        // Add web routes permanently
        $vir->createWebRoute($table);

        // Render temporary HTML preview
        $html = VirCreator::renderTemplate($table, $template);
        $this->line($html);

        Log::info('CrudVirtualCommand: completed CRUD and sidenav/web route updates');
        VirtualApiManager::ensureApiIsReady();
        Log::info('CrudVirtualCommand: Virtual API verified and Swagger refreshed.');
        $this->info('Virtual APi and Swagger documentation updated.');
    } catch (\Throwable $e) {
        $this->error("Error: " . $e->getMessage());
        Log::error('CrudVirtualCommand error', ['message' => $e->getMessage()]);
    }
}

}