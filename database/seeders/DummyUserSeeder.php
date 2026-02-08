<?php

namespace Database\Seeders;

use App\Constants\ManageStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an approved author user
        User::create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john@example.com',
            'username' => 'johndoe',
            'password' => Hash::make('password123'),
            'status' => ManageStatus::ACTIVE,
            'ec' => ManageStatus::VERIFIED, // email confirmed
            'sc' => ManageStatus::VERIFIED, // sms confirmed
            'author_status' => ManageStatus::AUTHOR_APPROVED,
        ]);

        // Create a pending author user
        User::create([
            'firstname' => 'Jane',
            'lastname' => 'Smith',
            'email' => 'jane@example.com',
            'username' => 'janesmith',
            'password' => Hash::make('password123'),
            'status' => ManageStatus::ACTIVE,
            'ec' => ManageStatus::VERIFIED,
            'sc' => ManageStatus::VERIFIED,
            'author_status' => ManageStatus::AUTHOR_PENDING,
        ]);

        // Create a regular user (not author)
        User::create([
            'firstname' => 'Bob',
            'lastname' => 'Wilson',
            'email' => 'bob@example.com',
            'username' => 'bobwilson',
            'password' => Hash::make('password123'),
            'status' => ManageStatus::ACTIVE,
            'ec' => ManageStatus::VERIFIED,
            'sc' => ManageStatus::VERIFIED,
            'author_status' => ManageStatus::IS_NOT_AUTHOR,
        ]);

        // Create a rejected author user
        User::create([
            'firstname' => 'Alice',
            'lastname' => 'Brown',
            'email' => 'alice@example.com',
            'username' => 'alicebrown',
            'password' => Hash::make('password123'),
            'status' => ManageStatus::ACTIVE,
            'ec' => ManageStatus::VERIFIED,
            'sc' => ManageStatus::VERIFIED,
            'author_status' => ManageStatus::AUTHOR_REJECTED,
        ]);

        $this->command->info('Dummy users created successfully!');
    }
}