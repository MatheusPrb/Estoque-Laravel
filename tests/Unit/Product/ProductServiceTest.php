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

    public function test_findById_should_return_product_when_exists()
    {
        $id = '123e4567-e89b-12d3-a456-426614174000';
        $product = new ProductModel(['id' => $id, 'name' => 'Produto Z', 'price' => 50, 'amount' => 3]);

        $this->repository->method('findById')->with($id)->willReturn($product);

        $result = $this->service->findById($id);

        $this->assertInstanceOf(ProductModel::class, $result);
        $this->assertEquals($product->name, $result->name);
    }

    public function test_findById_should_throw_exception_when_invalid_uuid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid UUID format');

        $this->service->findById('invalid-uuid');
    }

    public function test_findById_should_throw_exception_when_not_found()
    {
        $id = '123e4567-e89b-12d3-a456-426614174000';
        $this->repository->method('findById')->willReturn(null);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Produto '{$id}' não encontrado.");

        $this->service->findById($id);
    }
}
