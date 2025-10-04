<?php




namespace Tests\Unit;

use App\Enums\ProductStatusEnum;
use App\Models\ProductModel;
use App\DTO\ProductData;
use App\Repositories\ProductPersistenceInterface;
use App\Services\ProductService;
use PHPUnit\Framework\TestCase;

class ProductServiceEditTest extends TestCase

{
    private $repository;
    private ProductService $service;
    private ProductModel $product;
    private $id;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createMock(ProductPersistenceInterface::class);
        $this->service = new ProductService($this->repository);

        $this->id = ProductData::generateUuid();
        $this->product = new ProductModel([
            'id' => $this->id,
            'name' => 'Produto A',
            'price' => 100,
            'amount' => 10,
            'status' => ProductStatusEnum::ACTIVE->value,
        ]);
    }

    public function test_it_updates_price()
    {
        $data = new ProductData(ProductData::generateUuid(), price: 200);

        $this->repository
            ->method('findOne')
            ->willReturn(clone $this->product);

        $this->repository
            ->expects($this->once())
            ->method('update');

        $result = $this->service->edit($data);

        $this->assertEquals(200, $result->price);
    }

    public function test_it_updates_amount_and_sets_status_inactive_when_zero()
    {
        $data = new ProductData(ProductData::generateUuid(), amount: 0);

        $this->repository->method('findOne')->willReturn($this->product);
        $this->repository->expects($this->once())->method('update');

        $result = $this->service->edit($data);

        $this->assertEquals(0, $result->amount);
        $this->assertEquals(ProductStatusEnum::INACTIVE->value, $data->status);
    }

    public function test_it_updates_amount_and_sets_status_active_when_positive()
    {
        $this->product->amount = 0;

        $data = new ProductData(ProductData::generateUuid(), amount: 5);

        $this->repository
            ->method('findOne')
            ->willReturn(clone $this->product);

        $this->repository
            ->expects($this->once())
            ->method('update');

        $result = $this->service->edit($data);

        $this->assertEquals(5, $result->amount);
        $this->assertEquals(ProductStatusEnum::ACTIVE->value, $data->status);
    }

    public function test_it_updates_name_and_validates_uniqueness()
    {
        $data = new ProductData(ProductData::generateUuid(), name: 'Produto B');

        $this->repository
            ->method('findOne')
            ->willReturn(clone $this->product);

        $this->repository
            ->expects($this->once())
            ->method('update');

        $result = $this->service->edit($data);

        $this->assertEquals('Produto B', $result->name);
    }

    public function test_it_updates_status_and_sets_amount_zero_if_inactive()
    {
        $data = new ProductData(ProductData::generateUuid(), status: '0');

        $this->repository
            ->method('findOne')
            ->willReturn(clone $this->product);

        $this->repository
            ->expects($this->once())
            ->method('update');

        $result = $this->service->edit($data);

        $this->assertEquals(0, $result->amount);
        $this->assertEquals(ProductStatusEnum::INACTIVE->value, $result->status);
    }
}
