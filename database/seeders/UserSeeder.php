<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create(
            [
                'name'=>'admin',
                'email'=>'pablo@mascotasperdidaspy.org',
                'password'=>Hash::make('123456789+'),
                'email_verified_at'=>Carbon::now()
            ]
        );
        // $user->assignRole('Admin');
    }
}
