<?php
namespace App\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Country;

class CountrySeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $country = new Country();
        $tableName = $country->getTable();

        $first = DB::table($tableName)->first();

        if(!$first)
        {
            //Get all of the countries
            $countries = File::get("database/data/Countries.json");
            $countries = json_decode($countries, true);

            foreach ($countries as $country)
            {
                DB::table($tableName)->insert(array(
                    'country_name' => ((isset($country['name'])) ? $country['name'] : null),
                    'country_code' => ((isset($country['iso_3166_2'])) ? $country['iso_3166_2'] : null),
                    'country_code_alt' => ((isset($country['iso_3166_3'])) ? $country['iso_3166_3'] : null),
                    'calling_code' => ((isset($country['calling_code'])) ? $country['calling_code'] : null),
                    'currency_code' => ((isset($country['currency_code'])) ? $country['currency_code'] : null),
                    'citizenship' => ((isset($country['citizenship'])) ? $country['citizenship'] : null),
                    'currency_decimals' => ((isset($country['currency_decimals'])) ? $country['currency_decimals'] : null),
                ));
            }
        }
    }
}
