<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommercialCategoriesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('commercial_categories')->insert([
            ['name' => 'COMIDA RAPIDA'],
            ['name' => 'ROPA JUVENIL HOMBRES Y MUJERES'],
            ['name' => 'PELUQUERÍA Y SPA CANINO'],
            ['name' => 'JOYERIA'],
            ['name' => 'COLCHONES Y ARTICULOS PARA EL HOGAR'],
            ['name' => 'ÓPTICA'],
            ['name' => 'VENTA DE LENCERIA GERIATRICA Y ONCOLOGICA'],
            ['name' => 'ROPA CASUAL'],
            ['name' => 'CALZADO HOMBRE, MUJER Y NIÑO'],
            ['name' => 'ROPA DEPORTIVA'],
            ['name' => 'PERFUMERÍA Y ARTÍCULOS DE USO PERSONAL'],
            ['name' => 'ROPA DE CAMA, IMPLEMENTOS BAÑO, CORTINAS, ALFOMBRAS'],
            ['name' => 'PLASTICOS Y ARTICULOS PARA EL HOGAR'],
            ['name' => 'OPTICA, VENTA DE LENTES'],
            ['name' => 'ENTRENIMIENTO'],
            ['name' => 'VENTA DE CELULARES'],
            ['name' => 'PIZZERIA-RESTAURANTE'],
            ['name' => 'EQUIPO DEPORTIVO Y DE AVENTURA'],
            ['name' => 'ROPA HOMBRE, MUJER Y NIÑO'],
            ['name' => 'BOUTIQUE MOTOCICLISTAS'],
            ['name' => 'DEPILACIÓN Y BRONCEADO'],
            ['name' => 'PESTAÑERÍA, UÑAS Y FACIALES'],
            ['name' => 'TELEFONIA Y CELULARES'],
            ['name' => 'VENTA DE CALZADO DAMAS Y CABALLEROS'],
            ['name' => 'CEJAS'],
            ['name' => 'PELUQUERIA Y COSMETOLOGIA'],
            ['name' => 'JOYERIA Y ARTÍCULOS DE PLATA'],
            ['name' => 'DECORACION EN PELTRE DE UTENSILIOS DEL HOGAR'],
            ['name' => 'CRISTALERIA, PORCELANAS MENAJES'],
            ['name' => 'HELADERIA'],
            ['name' => 'VENTA DE ZAPATOS Y BIZUTERIA BRASILEÑA'],
            ['name' => 'LIBRERÍA'],
            ['name' => 'BARBERIA'],
            ['name' => 'JOYERIA'],
            ['name' => 'DEPILACION LASER'],
            ['name' => 'ACADEMIA DE ARTES MARCIALES'],
            ['name' => 'ROPA INFORNAL PARA HOMBRE Y MUJER'],
            ['name' => 'CAMISERIA Y CORBATAS'],
            ['name' => 'JOYERÍA Y PRESTACIÓN DE SERVICIOS'],
            ['name' => 'FARMACIA'],
            ['name' => 'ARTICULOS PARA EL HOGAR'],
            ['name' => 'UÑAS ACRILICAS'],
            ['name' => 'PELUQUERIA'],
            ['name' => 'VENTA Y MANTENIMIENTO DE ASPIRADORAS'],
            ['name' => 'MUEBLES ANTIGUOS'],
            ['name' => 'CALZADO PARA DAMAS'],
            ['name' => 'ROPA BEBE Y NIÑOS'],
            ['name' => 'MUÑECOS DE COLECCIÓN'],
            ['name' => 'COSTURA Y BAZAR'],
            ['name' => 'ROPA PARA INFORMAL PARA DAMAS'],
            ['name' => 'PC`S Y JUEGOS ELECTRONICOS'],
            ['name' => 'ROPA DE HOMBRE'],
            ['name' => 'ROPA INFORMAL PARA HOMBRE Y MUJER'],
            ['name' => 'ROPA MATERNAL , COSMETICOS'],
            ['name' => 'VENTA DE ROPA DE NIÑOS, PIJAMAS , ZAPATOS'],
            ['name' => 'ROPA Y VARIEDAD DE ARTICULOS'],
        ]);
    }
}
