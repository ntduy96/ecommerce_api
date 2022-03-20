<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Store;
use App\Models\User;
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
        $user = User::factory()->create();
        $stores = Store::factory()
            ->count(3)
            ->for($user)
            ->create();
        foreach ($stores as $store) {
            Product::factory()
                ->count(3)
                ->for($store)
                ->create();
        }
    }
}
