<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class RajaongkirProvincesSeeder extends Seeder
{
    public function run()
    {
        $apiKey = 'f416a024129c0cea6c1351bf39ffb39d';
        $response = Http::withHeaders(['key' => $apiKey])
                        ->get('https://api.rajaongkir.com/starter/province');
        
        if ($response->successful()) {
            $provinces = $response->json()['rajaongkir']['results'];
            foreach ($provinces as $province) {
                DB::table('rajaongkir_provinces')->insert([
                    'province_id' => $province['province_id'],
                    'province_name' => $province['province']
                ]);
            }
        }
    }
}
