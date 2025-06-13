<?php

namespace Tests\Unit\Services;

use App\Models\Product;
use App\Models\User;
use App\Services\ProductsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ProductsServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProductsService $service;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ProductsService();
        $this->user = User::factory()->create();
    }

    #[Test]
    public function it_can_get_all_products_with_default_pagination()
    {
        // Arrange
        Product::factory()->count(15)->create(['user_id' => $this->user->id]);
        $request = new Request();

        // Act
        $result = $this->service->getAllProducts($request);

        // Assert
        $this->assertCount(10, $result->items());
        $this->assertEquals(15, $result->total());
        $this->assertEquals(1, $result->currentPage());
        $this->assertEquals(10, $result->perPage());
    }

    #[Test]
    public function it_can_search_products_by_term()
    {
        // Arrange
        Product::factory()->create(['name' => 'Test Product', 'user_id' => $this->user->id]);
        Product::factory()->create(['name' => 'Another Product', 'user_id' => $this->user->id]);
        $request = new Request(['search' => 'Test']);

        // Act
        $result = $this->service->getAllProducts($request);

        // Assert
        $this->assertCount(1, $result->items());
        $this->assertEquals('Test Product', $result->items()[0]->name);
    }

    #[Test]
    public function it_can_sort_products()
    {
        // Arrange
        Product::factory()->create(['name' => 'Z Product', 'user_id' => $this->user->id]);
        Product::factory()->create(['name' => 'A Product', 'user_id' => $this->user->id]);
        $request = new Request([
            'sort_by' => 'name',
            'sort_direction' => 'asc'
        ]);

        // Act
        $result = $this->service->getAllProducts($request);

        // Assert
        $this->assertCount(2, $result->items());
        $this->assertEquals('A Product', $result->items()[0]->name);
        $this->assertEquals('Z Product', $result->items()[1]->name);
    }

    #[Test]
    public function it_can_filter_products_by_specific_column()
    {
        // Arrange
        Product::factory()->create(['name' => 'Test Product', 'price' => 100, 'user_id' => $this->user->id]);
        Product::factory()->create(['name' => 'Another Product', 'price' => 200, 'user_id' => $this->user->id]);
        $request = new Request(['price' => '100']);

        // Act
        $result = $this->service->getAllProducts($request);

        // Assert
        $this->assertCount(1, $result->items());
        $this->assertEquals(100, $result->items()[0]->price);
    }

    #[Test]
    public function it_can_change_per_page()
    {
        // Arrange
        Product::factory()->count(15)->create(['user_id' => $this->user->id]);
        $request = new Request(['perPage' => 5]);

        // Act
        $result = $this->service->getAllProducts($request);

        // Assert
        $this->assertCount(5, $result->items());
        $this->assertEquals(15, $result->total());
        $this->assertEquals(5, $result->perPage());
    }
} 