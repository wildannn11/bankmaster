<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $role_customer = Role::create(['name' => 'customer']);
        $role_bank = Role::create(['name' => 'bank']);
        // User::factory(10)->create();

        $user_customer = User::create([
            'name' => 'customer',
            'email' => 'customer@gmail.com',
            'password' => Hash::make('customer')
          ]);

          $user_bank = User::create([
            'name' => 'bank',
            'email' => 'bank@gmail.com',
            'password' => Hash::make('bank')
          ]);

          RoleUser::create([
            'user_id'=> $user_customer->id,
            'role_id' => $role_customer->id,
          ]);

          RoleUser::create([
            'user_id'=> $user_bank->id,
            'role_id' => $role_bank->id,
          ]);
    }
}
