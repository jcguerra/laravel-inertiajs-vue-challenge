<?php

namespace Tests\Feature\Controllers\Products;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class GetAllProductsControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    #[Test]
    public function it_can_render_products_page_with_paginated_products()
    {
        // Arrange
        Product::factory()->count(15)->create(['user_id' => $this->user->id]);

        // Act
        $response = $this->actingAs($this->user)->get(route('products.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('products/AllProducts')
            ->has('products.data', 10)
            ->where('products.total', 15)
            ->where('products.current_page', 1)
            ->where('products.per_page', 10)
        );
    }

    #[Test]
    public function it_can_search_products()
    {
        // Arrange
        Product::factory()->create(['name' => 'Test Product', 'user_id' => $this->user->id]);
        Product::factory()->create(['name' => 'Another Product', 'user_id' => $this->user->id]);

        // Act
        $response = $this->actingAs($this->user)->get(route('products.index', ['search' => 'Test']));

        // Assert
        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('products/AllProducts')
            ->has('products.data', 1)
            ->where('products.total', 1)
        );
    }

    #[Test]
    public function it_can_sort_products()
    {
        // Arrange
        Product::factory()->create(['name' => 'Z Product', 'user_id' => $this->user->id]);
        Product::factory()->create(['name' => 'A Product', 'user_id' => $this->user->id]);

        // Act
        $response = $this->actingAs($this->user)->get(route('products.index', [
            'sort_by' => 'name',
            'sort_direction' => 'asc'
        ]));

        // Assert
        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('products/AllProducts')
            ->has('products.data', 2)
            ->where('products.data.0.name', 'A Product')
            ->where('products.data.1.name', 'Z Product')
        );
    }

    #[Test]
    public function it_can_filter_products_by_specific_column()
    {
        // Arrange
        Product::factory()->create(['name' => 'Test Product', 'price' => 100, 'user_id' => $this->user->id]);
        Product::factory()->create(['name' => 'Another Product', 'price' => 200, 'user_id' => $this->user->id]);

        // Act
        $response = $this->actingAs($this->user)->get(route('products.index', ['price' => '100']));

        // Assert
        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('products/AllProducts')
            ->has('products.data', 1)
            ->where('products.data.0.price', 100)
        );
    }

    #[Test]
    public function it_can_change_per_page()
    {
        // Arrange
        Product::factory()->count(15)->create(['user_id' => $this->user->id]);

        // Act
        $response = $this->actingAs($this->user)->get(route('products.index', ['perPage' => 5]));

        // Assert
        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('products/AllProducts')
            ->has('products.data', 5)
            ->where('products.total', 15)
            ->where('products.per_page', 5)
        );
    }
} 