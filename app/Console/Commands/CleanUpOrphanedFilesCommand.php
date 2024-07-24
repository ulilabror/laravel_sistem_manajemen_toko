<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\CleanUpOrphanedFiles;

class CleanUpOrphanedFilesCommand extends Command
{
    protected $signature = 'cleanup:orphaned-files';
    protected $description = 'Delete files from storage that do not exist in the database';

    public function handle()
    {
        CleanUpOrphanedFiles::dispatch();
        $this->info('Orphaned files cleanup job dispatched.');
    }
}
