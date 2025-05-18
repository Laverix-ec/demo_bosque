<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('departments')->insert([
            [
                'name' => 'Seguridad'
            ],
            [
                'name' => 'Operaciones'
            ],
            [
                'name' => 'Sistemas'
            ],
            [
                'name' => 'Marketing'
            ],
            [
                'name' => 'Gestión'
            ],
        ]);
    }
}
