<?php

namespace Database\Seeders;

use App\Models\Perfume;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class PerfumesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $perfumes = [
            // Parfums de France
            [
                'supplier_id' => 1,
                'name' => 'Chanel N°5',
                'brand' => 'Chanel',
                'description' => 'Un parfum iconique aux notes florales aldéhydées, symbole d\'élégance intemporelle.',
                'price' => 85000,
                'stock_quantity' => 15,
                'is_available' => true,
            ],
            [
                'supplier_id' => 1,
                'name' => 'Dior Sauvage',
                'brand' => 'Dior',
                'description' => 'Parfum masculin frais et puissant aux notes boisées et épicées.',
                'price' => 75000,
                'stock_quantity' => 20,
                'is_available' => true,
            ],
            [
                'supplier_id' => 1,
                'name' => 'J\'adore',
                'brand' => 'Dior',
                'description' => 'Fragrance féminine florale luxueuse aux notes d\'ylang-ylang et de rose.',
                'price' => 80000,
                'stock_quantity' => 12,
                'is_available' => true,
            ],
            [
                'supplier_id' => 1,
                'name' => 'La Vie Est Belle',
                'brand' => 'Lancôme',
                'description' => 'Parfum gourmand floral aux notes d\'iris, patchouli et praline.',
                'price' => 70000,
                'stock_quantity' => 18,
                'is_available' => true,
            ],

            // Luxury Fragrances International
            [
                'supplier_id' => 2,
                'name' => 'Black Opium',
                'brand' => 'Yves Saint Laurent',
                'description' => 'Parfum oriental moderne aux notes de café, vanille et fleur d\'oranger.',
                'price' => 78000,
                'stock_quantity' => 10,
                'is_available' => true,
            ],
            [
                'supplier_id' => 2,
                'name' => 'Acqua di Gio',
                'brand' => 'Giorgio Armani',
                'description' => 'Fragrance aquatique fraîche pour homme aux notes marines.',
                'price' => 72000,
                'stock_quantity' => 25,
                'is_available' => true,
            ],
            [
                'supplier_id' => 2,
                'name' => 'Good Girl',
                'brand' => 'Carolina Herrera',
                'description' => 'Parfum féminin audacieux aux notes de jasmin, cacao et amande.',
                'price' => 76000,
                'stock_quantity' => 8,
                'is_available' => true,
            ],
            [
                'supplier_id' => 2,
                'name' => 'One Million',
                'brand' => 'Paco Rabanne',
                'description' => 'Parfum masculin oriental épicé aux notes de cannelle et cuir.',
                'price' => 68000,
                'stock_quantity' => 0,
                'is_available' => false,
            ],

            // Essences d'Afrique
            [
                'supplier_id' => 3,
                'name' => 'Karité Précieux',
                'brand' => 'Essences d\'Afrique',
                'description' => 'Parfum local aux notes de karité et bois précieux africains.',
                'price' => 45000,
                'stock_quantity' => 30,
                'is_available' => true,
            ],
            [
                'supplier_id' => 3,
                'name' => 'Fleur de Baobab',
                'brand' => 'Essences d\'Afrique',
                'description' => 'Fragrance féminine aux notes florales et fruitées d\'Afrique.',
                'price' => 42000,
                'stock_quantity' => 22,
                'is_available' => true,
            ],

            // Oriental Scents
            [
                'supplier_id' => 4,
                'name' => 'Oud Wood',
                'brand' => 'Tom Ford',
                'description' => 'Parfum oriental luxueux aux notes de bois de oud et épices.',
                'price' => 120000,
                'stock_quantity' => 5,
                'is_available' => true,
            ],
            [
                'supplier_id' => 4,
                'name' => 'Baccarat Rouge 540',
                'brand' => 'Maison Francis Kurkdjian',
                'description' => 'Parfum de luxe aux notes ambrées et boisées, universellement apprécié.',
                'price' => 150000,
                'stock_quantity' => 3,
                'is_available' => true,
            ],
            [
                'supplier_id' => 4,
                'name' => 'Arabian Nights',
                'brand' => 'Oriental Scents',
                'description' => 'Parfum oriental intense aux notes d\'ambre, musc et rose.',
                'price' => 95000,
                'stock_quantity' => 12,
                'is_available' => true,
            ],

            // European Perfumery
            [
                'supplier_id' => 5,
                'name' => 'Dolce & Gabbana Light Blue',
                'brand' => 'Dolce & Gabbana',
                'description' => 'Parfum frais et fruité aux notes de citron et pomme verte.',
                'price' => 65000,
                'stock_quantity' => 16,
                'is_available' => true,
            ],
            [
                'supplier_id' => 5,
                'name' => 'Versace Eros',
                'brand' => 'Versace',
                'description' => 'Parfum masculin frais et viril aux notes de menthe et vanille.',
                'price' => 70000,
                'stock_quantity' => 14,
                'is_available' => true,
            ],
            [
                'supplier_id' => 5,
                'name' => 'Gucci Bloom',
                'brand' => 'Gucci',
                'description' => 'Parfum floral féminin aux notes de jasmin et tubéreuse.',
                'price' => 82000,
                'stock_quantity' => 9,
                'is_available' => true,
            ],
        ];

        foreach ($perfumes as $perfume) {
            Perfume::create($perfume);
        }
    }
}
