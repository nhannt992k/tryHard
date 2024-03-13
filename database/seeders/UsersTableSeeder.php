<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Create user role
         $userRole = new Role();
         $userRole->id = 0;
         $userRole->name = 'user';
         $userRole->is_admin = false;
         $userRole->save();

        // Create admin role
        $adminRole = new Role();
        $adminRole->id = 1;
        $adminRole->name = 'admin';
        $adminRole->is_admin = true;
        $adminRole->save();

        // Create admin user
        $admin = new User();
        $admin->name = 'Admin User';
        $admin->email = 'admin@example.com';
        $admin->phone_number = '1234567890';
        $admin->password = Hash::make('password');
        $admin->role()->associate($adminRole);
        $admin->save();

       
       

        // Create 9 regular users
        for ($i = 1; $i <= 9; $i++) {
            $user = new User();
            $user->name = 'User ' . $i;
            $user->email = 'user' . $i . '@example.com';
            $user->phone_number = '123456789' . $i;
            $user->password = Hash::make('password');
            $user->role()->associate($userRole);
            $user->save();
        }
    
    }
}
