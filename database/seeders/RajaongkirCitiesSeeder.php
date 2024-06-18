<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class RajaongkirCitiesSeeder extends Seeder
{
    public function run()
    {
        $apiKey = 'f416a024129c0cea6c1351bf39ffb39d';
        $response = Http::withHeaders(['key' => $apiKey])
                        ->get('https://api.rajaongkir.com/starter/city');
        
        if ($response->successful()) {
            $cities = $response->json()['rajaongkir']['results'];
            foreach ($cities as $city) {
                DB::table('rajaongkir_cities')->insert([
                    'city_id' => $city['city_id'],
                    'city_name' => $city['city_name'] . " (" . $city['type'] . ")",
                    'type' => $city['type'],
                    'province_id' => $city['province_id'],
                    'postal_code' => $city['postal_code']
                ]);
            }
        }
    }
}
