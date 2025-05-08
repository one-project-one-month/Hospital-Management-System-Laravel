<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Role::create(['name' => 'admin','guard_name'=>'api']);
        Role::create(['name' => 'user','guard_name'=>'api']);
        Role::create(['name' => 'patient','guard_name'=>'api']);
        Role::create(['name' => 'doctor','guard_name'=>'api']);

        $this->call(MedicineSeeder::class);
        // User::factory(10)->create();

       $user= User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole(Role::findByName('user', 'api'));
    }
}
