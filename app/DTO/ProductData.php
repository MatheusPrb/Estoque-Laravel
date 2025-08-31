<?php

namespace App\DTO;

use App\Traits\Uuid;

class ProductData
{
    use Uuid;

    public function __construct(
        public string $id,
        public string $name,
        public float $price,
        public int $amount,
    ) {}
}
