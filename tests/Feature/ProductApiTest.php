<?php

namespace Tests\Feature;

use App\Product;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    /**
     * @var User
     */
    protected $user;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->user->products()->createMany(
            factory(Product::class, 3)->make()->toArray()
        );
    }

    protected function getHeaders () {
        return [
            'Authorization' => "Bearer {$this->user->api_token}"
        ];
    }

    protected function makeProductData() {
        return factory(Product::class)->make()->toArray();
    }

    protected function getRandomProduct() {
        return $this->user->products()->inRandomOrder()->first();
    }

    /**
     * /api/product not accessible to unauthorized users.
     *
     * @return void
     */
    public function testUnauthorizedProductsAccess()
    {
        $this->json('GET', '/api/product')->assertStatus(401);
        $this->json('POST', '/api/product')->assertStatus(401);
        $this->json('GET', '/api/product/1')->assertStatus(401);
        $this->json('PUT', '/api/product/1')->assertStatus(401);
        $this->json('PATCH', '/api/product/1')->assertStatus(401);
        $this->json('DELETE', '/api/product/1')->assertStatus(401);
    }

    /**
     * Test GET /api/product
     */
    public function testProductList() {
        $products = $this->user->products();
        $this->withHeaders($this->getHeaders())
            ->getJson('/api/product')
            ->assertStatus(200)
            ->assertJsonCount($products->count());
    }

    /**
     * Test POST /api/product
     */
    public function testProductPost() {
        $productData = $this->makeProductData();
        $response = $this->withHeaders($this->getHeaders())->postJson('/api/product', $productData);
        $response->assertStatus(201);
        $this->assertDatabaseHas('products', $productData);
    }

    /**
     * Test GET /api/product
     */
    public function testProductGet() {
        $product = $this->getRandomProduct();
        $response = $this->withHeaders($this->getHeaders())->getJson("/api/product/{$product->id}");
        $response->assertStatus(200);
        $response->assertJson($product->toArray());
    }

    /**
     * Test PUT /api/product
     */
    public function testProductPut() {
        $product = $this->getRandomProduct();
        $productData = $this->makeProductData();
        $response = $this->withHeaders($this->getHeaders())->putJson("/api/product/{$product->id}", $productData);
        $response->assertStatus(200);

        $productData['id'] = $product->getKey();
        $this->assertDatabaseHas('products', $productData);
    }

    /**
     * Test PATCH /api/product
     */
    public function testProductPatch() {
        $product = $this->getRandomProduct();
        $productData = $this->makeProductData();
        $response = $this->withHeaders($this->getHeaders())->patchJson("/api/product/{$product->id}", $productData);
        $response->assertStatus(200);

        $productData['id'] = $product->getKey();
        $this->assertDatabaseHas('products', $productData);
    }

    /**
     * Test DELETE /api/product
     */
    public function testProductDelete() {
        $product = $this->getRandomProduct();
        $response = $this->withHeaders($this->getHeaders())->deleteJson("/api/product/{$product->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('products', [$product->getKeyName() => $product->getKey()]);
    }

}
