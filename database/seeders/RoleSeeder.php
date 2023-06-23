<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = collect([
            ['nama_role' => 'admin'],
            ['nama_role' => 'user'],
        ]);

        $roles->each(function ($role) {
            \App\Models\Role::create($role);
        });

    }
}
