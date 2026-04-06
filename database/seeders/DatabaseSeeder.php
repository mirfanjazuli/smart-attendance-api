<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Data Lokasi (Kantor Fingerspot Surabaya)
        // Koordinat ini sekitar area Manyar Kertoarjo, Surabaya
        $office = Location::create([
            'name' => 'Smaat Surabaya Office',
            'latitude' => -7.2797, 
            'longitude' => 112.7681,
            'radius' => 100, // Radius 100 meter untuk geofencing
        ]);

        // 2. Seed Data Admin (Akun Kamu)
        User::create([
            'name' => 'Muhammad Irfan Jazuli',
            'email' => 'admin@smaat.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'position' => 'Lead Web Developer',
        ]);

        // 3. Seed Data Karyawan (Contoh)
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@smaat.com',
            'password' => Hash::make('password123'),
            'role' => 'employee',
            'position' => 'Field Engineer',
        ]);

        // 4. Buat 5 Karyawan Tambahan Secara Acak (Opsional)
        User::factory(5)->create([
            'role' => 'employee',
            'password' => Hash::make('password123'),
        ]);
    }
}
