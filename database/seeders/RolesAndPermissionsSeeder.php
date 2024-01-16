<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Repositories\Permissions;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
        // Permission::truncate();
        // Role::truncate();

        // $super_admin = Role::create(['name' => 'Super Admin']);
        $admin = Role::create(['name' => 'Admin']);

        $permissions = Permissions::all();
        $now = Carbon::now()->toDateTimeString();
        Permission::insert(array_map(fn ($permission) => ['name' => $permission, 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now], $permissions));

        $admin->givePermissionTo(Permission::all());
    }
}
