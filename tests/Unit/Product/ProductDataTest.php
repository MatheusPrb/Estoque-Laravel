<?php

namespace Tests\Unit;

use App\DTO\ProductData;
use App\Enums\ProductStatusEnum;
use PHPUnit\Framework\TestCase;

class ProductDataTest extends TestCase
{
    public function test_it_should_create_valid_product_data()
    {
        $dto = new ProductData(
            id: ProductData::generateUuid(),
            name: 'Produto Teste',
            status: ProductStatusEnum::ACTIVE->value,
            price: 100.0,
            amount: 10
        );

        $this->assertNotNull($dto->id);
        $this->assertEquals('Produto Teste', $dto->name);
        $this->assertEquals(ProductStatusEnum::ACTIVE->value, $dto->status);
        $this->assertEquals(100.0, $dto->price);
        $this->assertEquals(10, $dto->amount);
    }

    public function test_it_should_throw_exception_when_name_is_empty()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Name cannot be empty');

        new ProductData(
            id: ProductData::generateUuid(),
            name: '   '
        );
    }

    public function test_it_should_throw_exception_when_price_is_zero_or_negative()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Price must be greater than zero');

        new ProductData(
            id: ProductData::generateUuid(),
            name: 'Produto',
            price: 0
        );
    }

    public function test_it_should_throw_exception_when_amount_is_negative()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Amount cannot be negative');

        new ProductData(
            id: ProductData::generateUuid(),
            name: 'Produto',
            amount: -1
        );
    }

    public function test_it_should_throw_exception_when_status_is_invalid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid product status');

        new ProductData(
            id: ProductData::generateUuid(),
            name: 'Produto',
            status: 'invalid_status'
        );
    }

    public function test_it_should_accept_null_status_and_use_default_values()
    {
        $dto = new ProductData(
            id: ProductData::generateUuid(),
            name: 'Produto'
        );

        $this->assertNull($dto->status);
        $this->assertEquals('Produto', $dto->name);
    }

    public function test_productData_should_throw_exception_when_invalid_uuid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid UUID format');

        new ProductData('invalid-uuid');
    }
}
