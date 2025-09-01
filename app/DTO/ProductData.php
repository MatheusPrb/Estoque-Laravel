<?php

namespace App\DTO;

use App\Enums\ProductStatusEnum;
use App\Traits\Uuid;

class ProductData
{
    use Uuid;

    public function __construct(
        public string $id,
        public ?string $name = null,
        public ?string $status = null,
        public ?float $price = null,
        public ?int $amount = null,
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        self::checkUuid($this->id);
    
        if ($this->name !== null && trim($this->name) === '') {
            throw new \InvalidArgumentException('Name cannot be empty');
        }

        if ($this->price !== null && $this->price <= 0) {
            throw new \InvalidArgumentException('Price must be greater than zero');
        }

        if ($this->amount !== null && $this->amount < 0) {
            throw new \InvalidArgumentException('Amount cannot be negative');
        }

        if ($this->status !== null) {
            if (!ProductStatusEnum::isValidStatus($this->status)) {
                throw new \InvalidArgumentException('Invalid product status');
            }
        }
    }
}
