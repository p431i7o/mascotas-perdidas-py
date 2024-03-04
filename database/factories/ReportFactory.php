<?php

namespace Database\Factories;

use App\Models\AnimalKind;
use App\Models\City;
use App\Models\Department;
use App\Models\District;
use App\Models\Neighborhood;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    protected $model = Report::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'=>fake()->name(),
            'animal_kind_id'=>AnimalKind::inRandomOrder()->first()->id,
            'type'=>rand(1,100)>50?'Lost':'Found',
            'date'=>Carbon::now(),
            'description'=>fake('es_ES')->text(1000),
            'expiration'=>Carbon::now()->addDays(7),
            'address'=>fake('es_ES')->address(),
            'latitude'=>fake()->latitude(-27.18, -19.34),
            'longitude'=>fake()->longitude(-62.58, -54.46),
            'status'=>'Pending',
            'department_id'=>Department::inRandomOrder()->first(),
            'city_id'=>City::orderByRaw(DB::raw('rand()'))->first(),
            'district_id'=>District::inRandomOrder()->first(),
            'neighborhood_id'=>Neighborhood::inRandomOrder()->first(),
            'user_id'=>User::inRandomOrder()->first(),
            'attachments'=>'[{"mime": "image/jpeg", "width": 1500, "height": 500, "extension": "jpg", "file_name": "NjllRoVrlZHNiEs7etGKpCzv6FrGSgVxVcW9wkFK.jpg", "file_size": 130965, "sha1_content": "9ecce78d4dc0eb8c292b101eeacfc3cae77977e3", "original_name": "1500x500.jpg"}, {"mime": "image/jpeg", "width": 540, "height": 960, "extension": "jpg", "file_name": "cL3kKBo3pnKs5NILpFsZQIcLEVjyWvF4Hd9Fzmlq.jpg", "file_size": 161732, "sha1_content": "53cd9722682541537fba3d2dcf4238e6540ad416", "original_name": "10924724_10204711531382475_7433161084665932252_n.jpg"}, {"mime": "image/jpeg", "width": 800, "height": 450, "extension": "jpg", "file_name": "oy8gh8FZ6FPAme2HbxxHQoLZZ8sJMxbbBuM4qFjD.jpg", "file_size": 154885, "sha1_content": "00f6b29aac574bc3fbd4c547b5aa4831550e9758", "original_name": "crying-cat.jpg"}]',
            'observations'=>'Created with faker',
            'log'=>json_encode([Carbon::now()->toDateTimeString()=>["type"=> "created", "create_by_factory"=> true]])
        ];
    }
}
