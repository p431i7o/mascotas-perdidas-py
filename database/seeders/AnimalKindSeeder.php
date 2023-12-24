<?php

namespace Database\Seeders;

use App\Models\AnimalKind;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnimalKindSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AnimalKind::insert(
            [
                [
                    'name'=>'Canino',
                    'example'=>'Perro',
                    'created_at'=>Carbon::now()
                ],
                [
                    'name'=>'Felino',
                    'example'=>'Gato',
                    'created_at'=>Carbon::now()
                ],
                [
                    'name'=>'Ave',
                    'example'=>'Loro, Gallina, Cacatua',
                    'created_at'=>Carbon::now()
                ],
                [
                    'name'=>'Reptil',
                    'example'=>'Tortuga, Lagarto, Serpiente',
                    'created_at'=>Carbon::now()
                ],
            ]);
    }
}
