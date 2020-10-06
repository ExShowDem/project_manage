<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => new DateTime,
            'password' => bcrypt('12345678'),
        ]);

        $quan = User::create([
            'name' => 'QuanVH',
            'email' => 'quan@gmail.com',
            'email_verified_at' => new DateTime,
            'password' => bcrypt('12345678'),
        ]);

        $truong = User::create([
            'name' => 'TruongBT',
            'email' => 'truong@gmail.com',
            'email_verified_at' => new DateTime,
            'password' => bcrypt('12345678'),
        ]);

        $an = User::create([
            'name' => 'AnHV',
            'email' => 'an@gmail.com',
            'email_verified_at' => new DateTime,
            'password' => bcrypt('12345678'),
        ]);
        $admin->assignRole('admin');
        $quan->assignRole('admin');
        $truong->assignRole('admin');
        $an->assignRole('admin');

        factory(User::class, 100)->create();
    }
}
