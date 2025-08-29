<?php

namespace Tests\Unit;

use App\Domain\Product\Product;
use App\Repositories\ProductRepository;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function test_product_can_be_created()
    {
        $repository = $this->createMock(ProductRepository::class);

        $product = (new Product($repository))
            ->setId('18bd1916-80ae-4347-97d2-28b2a5da182a')
            ->setName('Teste')
            ->setPrice(10.5)
            ->setAmount(5)
        ;

        $this->assertEquals('18bd1916-80ae-4347-97d2-28b2a5da182a', $product->getId());
        $this->assertEquals('Teste', $product->getName());
        $this->assertEquals(10.5, $product->getPrice());
        $this->assertEquals(5, $product->getAmount());
    }

    public function test_should_throw_exception_when_product_id_is_invalid_uuid()
    {
        $product = new Product();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid UUID format');

        $product->setId('invalid-uuid');
    }

    public function test_should_throw_exception_when_product_price_is_invalid()
    {
        $product = new Product();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Price must be greater than zero');

        $product->setPrice(0);
    }
}
