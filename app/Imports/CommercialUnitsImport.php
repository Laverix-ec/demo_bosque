<?php

namespace App\Imports;

use App\Models\CommercialCategory;
use App\Models\CommercialUnit;
use App\Models\EvaluationCriteria;
use App\Models\Tenant;
use App\Models\UnitScore;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class CommercialUnitsImport implements ToCollection, WithHeadingRow
{

    use Importable, SkipsErrors, SkipsFailures;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $unit = CommercialUnit::query()->create([
                'zone' => $row['zona'],
                'local_code' => $row['numero'],
                'name' => $row['local'],
                'category_id' => self::getCategoryId($row['categoria']),
                'ruc' => $row['ruc'],
                'property_code' => $row['predio'],
                'location' => $row['ubicacion'],
                'tenant_id' => self::getTenantId($row['nombre2'], $row['mail3'], $row['celular4']),
                'co_tenant_id' => self::getTenantId($row['nombre'], $row['mail'], $row['celular'])
            ]);

            EvaluationCriteria::query()->get()->map(function (EvaluationCriteria $value) use ($unit, $row) {
                if ($row[Str::slug($value->name, '_')]) {
                    UnitScore::query()->create([
                        'commercial_unit_id' => $unit->id,
                        'evaluation_criteria_id' => $value->id,
                        'score' => $row[Str::slug($value->name, '_')],
                        'comment' => $row['comentario_' . Str::slug($value->name, '_')],
                        'evaluation_date' => now()
                    ]);
                }
            });
        }
    }

    public function getCategoryId($name)
    {
        if (!$name) {
            return null;
        }
        $cat = CommercialCategory::query()->firstOrCreate(
            ['name' => $name]
        );
        return $cat->id;
    }

    public function getTenantId($name, $email, $phone)
    {
        if (!$name) {
            return null;
        }
        $tenant = Tenant::query()->firstOrCreate(
            ['name' => $name, 'email' => $email, 'phone' => $phone]
        );
        return $tenant->id;
    }
}
