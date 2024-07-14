<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\File;

class FileSeeder extends Seeder
{
    public function run()
    {
        File::factory()->create();
    }
}
