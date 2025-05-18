<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EvaluationCriteriaSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('evaluation_criteria')->insert([
            [
                'name' => 'MANT. CATEGORÍA',
                'max_score' => 4,
            ],
            [
                'name' => 'ROTULACIÓN',
                'max_score' => 3,
            ],
            [
                'name' => 'RESPETO HORARIO CENTRO COMERCIAL',
                'max_score' => 4,
            ],
            [
                'name' => 'DECORACIÓN E ILUMINACIÓN',
                'max_score' => 2,
            ],
            [
                'name' => 'VITRINA',
                'max_score' => 2,
            ],
            [
                'name' => 'PAGO PUNTUAL',
                'max_score' => 3,
            ],
            [
                'name' => 'RESPETO ÁREAS COMUNALES',
                'max_score' => 2,
            ],
        ]);
    }
}
