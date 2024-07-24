<?php

namespace App\Jobs;

use App\Models\File;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CleanUpOrphanedFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle()
    {
        $allFiles = Storage::allFiles('public/files');
        $dbFiles = File::pluck('path')->toArray();
        print_r($dbFiles);
        $orphanedFiles = array_diff($allFiles, $dbFiles);

        foreach ($orphanedFiles as $file) {
            if (!Str::startsWith($file, 'public/files/')) {
                continue; // Ensure we are only dealing with files in the 'public/files' directory
            }
            Storage::delete($file);
            Log::info("Deleted orphaned file: {$file}");
        }
    }
}
