<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Divisi;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Log;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $division = Divisi::where('name', 'Backend')->firstOrFail();

        $karyawans = [
            [
                'name' => 'Sabil',
                'phone' => '081775212021',
                'division_id' => $division->id,
                'position' => 'Junior Backend Developer',
                'image' => 'sabil.jpg',
            ],
            [
                'name' => 'Vanya',
                'phone' => '087654321098',
                'division_id' => $division->id,
                'position' => 'Junior Backend Developer',
                'image' => 'sabil.jpg',
            ],
        ];


        foreach ($karyawans as $karyawan) {
            $karyawan['uuid'] = Str::uuid()->toString();
            Karyawan::create($karyawan);
        }
    }
}
