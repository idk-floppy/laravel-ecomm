<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Order;
use App\Models\OrderStatuses;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(1)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Admin',
        //     'email' => 'admin@local',
        //     'password' => Hash::make('admin123'),
        // ]);
        $defaultOrderStatuses = [
            ['id' => 1, 'text' => 'SUBMITTED'],
            ['id' => 2, 'text' => 'CANCELLED'],
            ['id' => 3, 'text' => 'APPROVED'],
            ['id' => 4, 'text' => 'PROCESSING'],
            ['id' => 5, 'text' => 'SHIPPING'],
            ['id' => 6, 'text' => 'CLOSED'],
        ];

        foreach ($defaultOrderStatuses as $status) {
            OrderStatuses::create($status);
        }

        $this->call([
            ProductSeeder::class,
            OrderSeeder::class,
            OrderItemsSeeder::class,
        ]);
    }
}
