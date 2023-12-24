<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeometrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Migrando departamentos');
        $departments_query = file_get_contents('database/seeders/geometry_departments.sql');
        DB::unprepared($departments_query);

        $this->command->info("Migrando ciudades");
        $departments_query = file_get_contents('database/seeders/geometry_cities.sql');
        DB::unprepared($departments_query);

        $this->command->info("Migrando Distritos");
        $departments_query = file_get_contents('database/seeders/geometry_districts.sql');
        DB::unprepared($departments_query);

        $this->command->info("Migrando Barrios");
        $departments_query = file_get_contents('database/seeders/geometry_neighborhoods.sql');
        DB::unprepared($departments_query);
        
        $this->command->info("Fin geometrias");
    }
}
