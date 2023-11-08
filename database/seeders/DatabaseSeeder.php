<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Coupon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create();
        // ---------------------------- 10 Categories----------------------------------------------

        // for ($i = 0; $i < 10; $i++) {
        //     Category::create([
        //         'name' => $faker->word(),
        //         'slug' => $faker->slug(),
        //         'description' => $faker->sentence(),
        //         'image' => 'https://via.placeholder.com/500x500.png?text=' . urlencode($faker->word()),
        //         'status' => $faker->randomElement([1, 0])
        //     ]);
        // }


        // ----------------------------Products----------------------------------------------

        // $categories = Category::all();
        // foreach ($categories as $category) {
        //     for ($i = 0; $i < 3; $i++) {
        //         $product = Product::create([
        //             'category_id' => $category->id,
        //             'name' => $faker->word(2, true),
        //             'slug' => $faker->unique()->slug(),
        //             'description' => $faker->sentence(),
        //             'original_price' => $faker->randomFloat(2, 10, 100),
        //             'selling_price' => $faker->randomFloat(2, 10, 100),
        //             'quantity' => $faker->numberBetween(1, 100),
        //             'trending' => $faker->randomElement([1, 0]),
        //             'featured' => $faker->randomElement([1, 0]),
        //             'status' => $faker->randomElement([1, 0])
        //         ]);

        //         // Create 2 images for each product
        //         for ($j = 0; $j < 2; $j++) {
        //             $product->productImages()->create([
        //                 'image' => 'https://via.placeholder.com/500x500.png?text=' . urlencode($faker->word())
        //             ]);
        //         }
        //     }
        // }
        // ----------------------------users-------------------------------------------------
        // for ($i = 0; $i < 7; $i++) {
        //     User::create([
        //         'full_name' => $faker->name,
        //         'email' => $faker->unique()->safeEmail,
        //         'password' => Hash::make('Taghazout-Market'),
        //         'role' => $faker->randomElement(['admin', 'user']),
        //     ]);
        // }
        // ----------------------------coupon-------------------------------------------------
        // for ($i = 1; $i <= 10; $i++) {
            //     $coupon = new Coupon();
            //     $coupon->code = $faker->unique()->regexify('[A-Z0-9]{10}');
            //     $coupon->type = $faker->randomElement(['percent', 'fixed']);
            //     $coupon->value = $faker->randomFloat(2, 5, 50);
            //     $coupon->cart_value = $faker->randomFloat(2, 50, 100);
            //     $coupon->save();
            // }
            
            // ----------------------------Users -------------------------------------------------

            $users = [
                [
                    'full_name' => 'Mohammed Ali',
                    'email' => 'mohammed.ali@example.ma',
                    'phone' => '1234567890',
                    'address' => '123 Main St, City',
                    'password' => 'mohammed.ali@example.ma',
                ],
                [
                    'full_name' => 'Fatima Zahra',
                    'email' => 'fatima.zahra@example.ma',
                    'phone' => '1234567890',
                    'address' => '456 Elm St, City',
                    'password' => 'fatima.zahra@example.ma',
                ],
           
                [
                    'full_name' => 'Ahmed Hassan',
                    'email' => 'ahmed.hassan@example.ma',
                    'phone' => '1234567890',
                    'address' => 'Random Address',
                    'password' => 'ahmed.hassan@example.ma',
                ],
                [
                    'full_name' => 'Amina Belkadi',
                    'email' => 'amina.belkadi@example.ma',
                    'phone' => '1234567890',
                    'address' => 'Random Address',
                    'password' => 'amina.belkadi@example.ma',
                ],
                [
                    'full_name' => 'Youssef El Mansouri',
                    'email' => 'youssef.elmansouri@example.ma',
                    'phone' => '1234567890',
                    'address' => 'Random Address',
                    'password' => 'youssef.elmansouri@example.ma',
                ],
                [
                    'full_name' => 'Khadija Hamidi',
                    'email' => 'khadija.hamidi@example.ma',
                    'phone' => '1234567890',
                    'address' => 'Random Address',
                    'password' => 'khadija.hamidi@example.ma',
                ],
                [
                    'full_name' => 'Omar Ben Salah',
                    'email' => 'omar.bensalah@example.ma',
                    'phone' => '1234567890',
                    'address' => 'Random Address',
                    'password' => 'omar.bensalah@example.ma',
                ],
                [
                    'full_name' => 'Salma El Amrani',
                    'email' => 'salma.elamrani@example.ma',
                    'phone' => '1234567890',
                    'address' => 'Random Address',
                    'password' => 'salma.elamrani@example.ma',
                ],
                [
                    'full_name' => 'Karim Bouchra',
                    'email' => 'karim.bouchra@example.ma',
                    'phone' => '1234567890',
                    'address' => 'Random Address',
                    'password' => 'karim.bouchra@example.ma',
                ],
                [
                    'full_name' => 'Laila Fassi',
                    'email' => 'laila.fassi@example.ma',
                    'phone' => '1234567890',
                    'address' => 'Random Address',
                    'password' => 'laila.fassi@example.ma',
                ],
            ];
        foreach ($users as $user) {
            User::create([
                'full_name' => $user['full_name'],
                'email' => $user['email'],
                'address' => $user['address'],
                'phone' => $user['phone'],
                'password' => Hash::make($user['password']),
            ]);
        }
    }
}


        
    

