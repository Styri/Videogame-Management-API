<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();
        $regularUserRole = Role::where('name', 'Regular User')->first();

        User::firstOrCreate(
            ['email' => 'admin@gamehub.com'],
            [
                'name' => 'Alexander Wright',
                'password' => Hash::make('password123'),
                'role_id' => $adminRole->id,
                'email_verified_at' => now()
            ]
        );

        $firstNames = [
            'Emma', 'Liam', 'Olivia', 'Noah', 'Ava', 'Ethan', 'Isabella', 'Lucas',
            'Sophia', 'Mason', 'Mia', 'Logan', 'Charlotte', 'James', 'Amelia',
            'Benjamin', 'Harper', 'William', 'Evelyn', 'Michael', 'Abigail', 'Alexander',
            'Emily', 'Daniel', 'Elizabeth', 'Henry', 'Sofia', 'Sebastian', 'Avery',
            'Jack', 'Ella', 'Owen', 'Madison', 'Gabriel', 'Scarlett', 'Matthew',
            'Victoria', 'Leo', 'Aria', 'Julian', 'Grace', 'David', 'Chloe', 'Isaac',
            'Zoey', 'Jayden', 'Penelope', 'Luke', 'Riley', 'Nathan'
        ];

        $lastNames = [
            'Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller',
            'Davis', 'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez',
            'Wilson', 'Anderson', 'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin',
            'Lee', 'Perez', 'Thompson', 'White', 'Harris', 'Sanchez', 'Clark',
            'Ramirez', 'Lewis', 'Robinson', 'Walker', 'Young', 'Allen', 'King',
            'Wright', 'Scott', 'Torres', 'Nguyen', 'Hill', 'Flores', 'Green',
            'Adams', 'Nelson', 'Baker', 'Hall', 'Rivera', 'Campbell', 'Mitchell',
            'Carter', 'Roberts'
        ];

        for ($i = 0; $i < 50; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $email = strtolower($firstName . '.' . $lastName . rand(1, 999) . '@gmail.com');

            User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $firstName . ' ' . $lastName,
                    'password' => Hash::make('password123'),
                    'role_id' => $regularUserRole->id,
                    'email_verified_at' => now()->subDays(rand(1, 365))
                ]
            );
        }
    }
}
