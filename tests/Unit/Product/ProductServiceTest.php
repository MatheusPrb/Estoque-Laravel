<?php

namespace Tests\Unit\Services;

use App\DTO\ProductData;
use App\Models\ProductModel;
use App\Repositories\ProductPersistenceInterface;
use App\Services\ProductService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\TestCase;

class ProductServiceTest extends TestCase
{
    private $repository;
    private ProductService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createMock(ProductPersistenceInterface::class);
        $this->service = new ProductService($this->repository);
    }

    public function test_create_product_success()
    {
        $data = new ProductData('123e4567-e89b-12d3-a456-426614174000', 'Produto X', 100.0, 10);

        $this->repository->method('validateName')->willReturn(false);
        $this->repository->method('create')->willReturn(new ProductModel([
            'id' => $data->id,
            'name' => $data->name,
            'price' => $data->price,
            'amount' => $data->amount,
        ]));

        $product = $this->service->create($data);

        $this->assertInstanceOf(ProductModel::class, $product);
        $this->assertEquals($data->name, $product->name);
    }

    public function test_create_should_throw_exception_if_name_already_exists()
    {
        $data = new ProductData('123e4567-e89b-12d3-a456-426614174000', 'Produto X', 100.0, 10);

        $this->repository->method('validateName')->willReturn(true);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Produto '{$data->name}' já cadastrado.");

        $this->service->create($data);
    }

    public function test_findAll_should_return_products()
    {
        $products = new Collection([
            new ProductModel(['id' => 'uuid1', 'name' => 'A', 'price' => 10, 'amount' => 2]),
        ]);

        $this->repository->method('findAll')->willReturn($products);

        $result = $this->service->findAll();

        $this->assertCount(1, $result);
        $this->assertEquals('A', $result->first()->name);
    }

    public function test_findAll_should_throw_exception_if_empty()
    {
        $this->repository->method('findAll')->willReturn(new Collection());

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Nenhum produto encontrado.');

        $this->service->findAll();
    }

    public function test_findAllPaginated_should_return_paginated_products()
    {
        $products = $this->createMock(LengthAwarePaginator::class);
        $products->method('isEmpty')->willReturn(false);

        $this->repository->method('findAllPaginated')->willReturn($products);

        $result = $this->service->findAllPaginated(5, 1);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    public function test_findAllPaginated_should_throw_exception_when_page_or_perPage_invalid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Parâmetros de paginação inválidos.');

        $this->service->findAllPaginated(0, -1);
    }

    public function test_findAllPaginated_should_throw_exception_when_products_are_empty()
    {
        $products = $this->createMock(LengthAwarePaginator::class);
        $products->method('isEmpty')->willReturn(true);

        $this->repository->method('findAllPaginated')->willReturn($products);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Nenhum produto encontrado.');

        $result = $this->service->findAllPaginated(5, 1);
    }

    public function test_findOne_should_return_product_when_exists()
    {
        $data = new ProductData('123e4567-e89b-12d3-a456-426614174000');
        $product = new ProductModel(['id' => $data->id, 'name' => 'Produto Z', 'price' => 50, 'amount' => 3]);

        $this->repository->method('findOne')->with($data)->willReturn($product);

        $result = $this->service->findOne($data);

        $this->assertInstanceOf(ProductModel::class, $result);
        $this->assertEquals($product->name, $result->name);
    }

    public function test_productData_should_throw_exception_when_invalid_uuid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid UUID format');

        $data = new ProductData('invalid-uuid');
    }

    public function test_findOne_should_throw_exception_when_not_found()
    {
        $data = new ProductData('2c6b392c-379d-4ffd-ba74-c24e4340af45');
        $this->repository->method('findOne')->with($data)->willReturn(null);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Produto '{$data->id}' não encontrado.");

        $this->service->findOne($data);
    }

    public function test_edit_should_update_price_only()
    {
        $data = new ProductData(
            ProductData::generateUuid(),
            null,
            200.0,
            null
        );

        $product = new ProductModel(
            [
                'id' => $data->id,
                'name' => 'Produto Y',
                'price' => 100.0,
                'amount' => 5
            ]
        );

        $this->repository
            ->method('findOne')
            ->with($data)
            ->willReturn($product)
        ;
    
        $this->repository->method('update')->willReturn($product);

        $result = $this->service->edit($data);

        $this->assertEquals(200.0, $result->price);
        $this->assertEquals('Produto Y', $result->name);
        $this->assertEquals(5, $result->amount);
    }

    public function test_edit_should_update_amount_only()
    {
        $data = new ProductData('uuid-edit-2', null, null, 99);
        $product = new ProductModel(['id' => $data->id, 'name' => 'Produto W', 'price' => 50.0, 'amount' => 10]);

        $this->repository->method('findOne')->with($data)->willReturn($product);
        $this->repository->method('update')->willReturn($product);

        $result = $this->service->edit($data);

        $this->assertEquals(99, $result->amount);
        $this->assertEquals('Produto W', $result->name);
        $this->assertEquals(50.0, $result->price);
    }

    public function test_edit_should_update_name_only()
    {
        $data = new ProductData('uuid-edit-3', 'Novo Nome', null, null);
        $product = new ProductModel(['id' => $data->id, 'name' => 'Produto V', 'price' => 70.0, 'amount' => 7]);

        $this->repository->method('findOne')->with($data)->willReturn($product);
        $this->repository->method('validateName')->willReturn(false);
        $this->repository->method('update')->willReturn($product);

        $result = $this->service->edit($data);

        $this->assertEquals('Novo Nome', $result->name);
        $this->assertEquals(70.0, $result->price);
        $this->assertEquals(7, $result->amount);
    }

    public function test_edit_should_throw_exception_if_name_already_exists()
    {
        $data = new ProductData('uuid-edit-4', 'Nome Existente', null, null);
        $product = new ProductModel(['id' => $data->id, 'name' => 'Produto T', 'price' => 80.0, 'amount' => 8]);

        $this->repository->method('findOne')->with($data)->willReturn($product);
        $this->repository->method('validateName')->willReturn(true);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Produto 'Nome Existente' já cadastrado.");

        $this->service->edit($data);
    }

    public function test_edit_should_update_multiple_fields()
    {
        $data = new ProductData('uuid-edit-5', 'Produto Atualizado', 300.0, 15);
        $product = new ProductModel(['id' => $data->id, 'name' => 'Produto S', 'price' => 100.0, 'amount' => 5]);

        $this->repository->method('findOne')->with($data)->willReturn($product);
        $this->repository->method('validateName')->willReturn(false);
        $this->repository->method('update')->willReturn($product);

        $result = $this->service->edit($data);

        $this->assertEquals('Produto Atualizado', $result->name);
        $this->assertEquals(300.0, $result->price);
        $this->assertEquals(15, $result->amount);
    }
}
