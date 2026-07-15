<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $makanan = Category::where('name', 'Makanan Berat')->first();
        $minuman = Category::where('name', 'Minuman')->first();
        $snack = Category::where('name', 'Snack')->first();

        $products = [
            [
                'category_id' => $makanan->id,
                'name' => 'Nasi Goreng Spesial',
                'description' => 'Nasi goreng dengan bumbu rahasia, ditambah telur ceplok dan ayam suwir.',
                'price' => 25000,
                'stock' => 50,
                'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            ],
            [
                'category_id' => $makanan->id,
                'name' => 'Ayam Bakar Madu',
                'description' => 'Ayam bakar dengan olesan madu murni yang manis dan gurih, disajikan dengan sambal.',
                'price' => 30000,
                'stock' => 30,
                'image' => 'https://images.unsplash.com/photo-1598514982205-f36b96d1e8d4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            ],
            [
                'category_id' => $minuman->id,
                'name' => 'Es Kopi Susu Aren',
                'description' => 'Perpaduan espresso pekat dengan susu segar dan gula aren asli.',
                'price' => 18000,
                'stock' => 100,
                'image' => 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            ],
            [
                'category_id' => $minuman->id,
                'name' => 'Matcha Latte Dingin',
                'description' => 'Minuman matcha Jepang premium dicampur dengan susu segar.',
                'price' => 22000,
                'stock' => 50,
                'image' => 'https://images.unsplash.com/photo-1536420121532-64732c5c4f22?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            ],
            [
                'category_id' => $snack->id,
                'name' => 'Kentang Goreng Truffle',
                'description' => 'Kentang goreng renyah dengan taburan keju parmesan dan minyak truffle.',
                'price' => 20000,
                'stock' => 40,
                'image' => 'https://images.unsplash.com/photo-1576107232684-1279f3908581?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            ],
            [
                'category_id' => $snack->id,
                'name' => 'Dimsum Mentai',
                'description' => 'Dimsum ayam udang dengan saus mentai bakar yang lumer di mulut.',
                'price' => 28000,
                'stock' => 30,
                'image' => 'https://images.unsplash.com/photo-1496116218417-1a781b1c416c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            ],
        ];

        foreach ($products as $product) {
            $product['slug'] = Str::slug($product['name']);
            Product::create($product);
        }
    }
}
