<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        \App\Models\User::factory()->create([
            'name' => 'Karim Tarek',
            'email' => 'kariimttarek@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('12345678'), // password
            'role' => 1
        ]);
//        \App\Models\Category::create([
//            'id' => '1',
//            'name' => 'Carrier',
//            'name_ar' => 'كارير',
//        ]);
//        \App\Models\Category::create([
//            'id' => '2',
//            'name' => 'Midea',
//            'name_ar' => 'ميديا',
//        ]);

        $this->call([
            ActivityTypes::class,
            CountryCodes::class,
            CurrencyCodes::class,
            GovCities::class,
            TaxTypes::class,
            TaxSubTypes::class,
            UnitTypes::class,
            ProductType::class
        ]);
    }
}
