<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        DB::table((new Project)->getTable())->insert([
            ['name' => 'Foo'],
            ['name' => 'Bar'],
            ['name' => 'Baz'],
        ]);
    }
}
