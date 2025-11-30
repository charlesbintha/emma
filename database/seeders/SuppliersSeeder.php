<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SuppliersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $suppliers = [
            [
                'name' => 'Parfums de France',
                'email' => 'contact@parfumsdefrance.fr',
                'phone' => '+33 1 23 45 67 89',
                'address' => '123 Avenue des Champs-Élysées, 75008 Paris, France',
            ],
            [
                'name' => 'Luxury Fragrances International',
                'email' => 'info@luxuryfragrances.com',
                'phone' => '+1 212 555 1234',
                'address' => '456 Fifth Avenue, New York, NY 10018, USA',
            ],
            [
                'name' => 'Essences d\'Afrique',
                'email' => 'contact@essencesafrique.ci',
                'phone' => '+225 27 20 30 40 50',
                'address' => 'Zone 4, Marcory, Abidjan, Côte d\'Ivoire',
            ],
            [
                'name' => 'Oriental Scents',
                'email' => 'sales@orientalscents.ae',
                'phone' => '+971 4 123 4567',
                'address' => 'Dubai Mall, Downtown Dubai, UAE',
            ],
            [
                'name' => 'European Perfumery',
                'email' => 'info@europeanperfumery.it',
                'phone' => '+39 02 1234 5678',
                'address' => 'Via Monte Napoleone, 20121 Milano, Italia',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
