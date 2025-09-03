<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductModel;
use App\Enums\ProductStatusEnum;
use App\Traits\Uuid;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    use Uuid;
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 100; $i++) {
            $isDeleted = $faker->boolean(25);

            $isDeleted ? $status = ProductStatusEnum::INACTIVE->value : $status = ProductStatusEnum::ACTIVE->value;

            ProductModel::create([
                'id' => $this->generateUuid(),
                'name' => $faker->words(2, true),
                'status' => $status,
                'price' => $faker->randomFloat(2, 10, 1000),
                'amount' => $isDeleted ? 0 : $faker->numberBetween(1, 50),
                'deleted_at' => $isDeleted ? Carbon::now() : null
            ]);
        }
    }
}
