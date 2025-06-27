<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class EncryptedPasswordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('encrypted_passwords')->insert([
            'first_password' => Crypt::encrypt('12345678'),
            'second_password' => Crypt::encrypt('852852852'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
} 