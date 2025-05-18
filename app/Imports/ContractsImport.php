<?php

namespace App\Imports;

use App\Models\Contract;
use App\Models\Department;
use App\Models\Provider;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Str;

class ContractsImport implements ToModel, WithHeadingRow
{

    use Importable, SkipsErrors, SkipsFailures;

    /**
     * @param array $row
     *
     * @return Contract
     */
    public function model(array $row): Contract
    {
        return new Contract([
            'contract_number' => $row['numero_de_contrato'],
            'contract_object' => $row['objeto_del_contrato'],
            'internal_admin_id' => 1,
            'department_id' => self::getDepartmentId($row['departamento']),
            'provider_id' => self::getProviderId($row),
            'product_service' => $row['productoservicio'],
            'start_date' => Date::excelToDateTimeObject($row['fecha_de_inicio']),
            'end_date' => Date::excelToDateTimeObject($row['fecha_de_finalizacion']),
            'status' => $row['estado'],
            'addenda' => $row['adendas'],
            'auto_renewal' => $row['renovacion_automatica'] === 'SI',
            'observation' => $row['observacion'],
            'account_code' => $row['codigo_de_cuenta'],
            'payment_terms' => $row['terminos_de_pago'],
            'contract_cost' => $row['costo_del_contrato'],
            'approved_additional_costs' => $row['costos_adicionales_aprobados'],
            'approved_budget' => $row['presupuesto_de_contrato_aprobado'],
            'total_cost' => $row['costo_total'],
            'cost_vs_budget' => $row['costo_vs_presupuesto']
        ]);
    }

    public function getDepartmentId($name)
    {
        $dep = Department::query()->firstWhere('name', $name);
        return $dep ? $dep->id : null;
    }

    public function getProviderId($row)
    {
        $provider = Provider::query()->firstOrCreate(
            ['ruc' => $row['ruc_del_proveedor']],
            [
                'commercial_name' => $row['nombre_comercial_del_proveedor'],
                'legal_name' => $row['razon_social'],
                'contact_name' => $row['contacto_del_proveedor'],
                'contact_email' => $row['correo_electronico_del_proveedor']
            ]
        );
        return $provider->id;
    }
}
