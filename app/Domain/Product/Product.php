<?php

namespace App\Domain\Product;

use App\Traits\Uuid;

class Product
{
    use Uuid;

    private ?ProductPersistenceInterface $persistence;
    private string $id;
    private string $name;
    private float $price;
    private int $amount;

    public function __construct(?ProductPersistenceInterface $persistence = null)
    {
        $this->persistence = $persistence;
    }

    public function getPeristence(): ?ProductPersistenceInterface
    {
       return $this->persistence;
    }

    public function setId(string $id): Product
    {
        if (!$this->isValidUuid($id)) {
            throw new \InvalidArgumentException('Invalid UUID format');
        }

        $this->id = $id;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setName(string $name): Product
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setPrice(float $price): Product
    {
        $this->price = $price;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setAmount(int $amount): Product
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function saveProduct(): void
    {
        $this->getPeristence()->save($this);
    }

    public function loadById(): bool
    {
        return $this->getPeristence()->loadById($this);
    }
}