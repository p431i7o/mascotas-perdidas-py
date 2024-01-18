<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Repositories\Permissions;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        DB::table('model_has_permissions')->delete();
        DB::table('permissions')->delete();
        //  Permission::truncate();
        // Role::truncate();

        // $super_admin = Role::create(['name' => 'Super Admin']);
        $admin=false;
        if(Role::where('name','Admin')->count()<=0){

            $admin = Role::create(['name' => 'Admin']);
        }

        $permissions = Permissions::all();
        $now = Carbon::now()->toDateTimeString();
        Permission::insert(array_map(fn ($permission) => ['name' => $permission, 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now], $permissions));

        if($admin){
            $admin->givePermissionTo(Permission::all());
        }
    }
}
