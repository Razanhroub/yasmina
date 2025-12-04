<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        DB::table('roles')->truncate(); //making sure to delete any old data in the table

        //adding the main three roles so we can assign them to the role id table
        Role::create(['id' => 1, 'name' => 'admin',   'guard_name' => 'web']);
        Role::create(['id' => 2, 'name' => 'teacher', 'guard_name' => 'web']);
        Role::create(['id' => 3, 'name' => 'student', 'guard_name' => 'web']);
    }
}
