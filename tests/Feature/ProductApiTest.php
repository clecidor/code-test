<?php

namespace Tests\Feature;

use App\Product;
use \Symfony\Component\HttpFoundation\Response;


class ProductApiTest extends ApiTestCase
{

    /**
     * @return array
     */
    protected function makeProductData() {
        return factory(Product::class)->make()->toArray();
    }

    /**
     * /api/product not accessible to unauthorized users.
     *
     * @return void
     */
    public function testUnauthorizedProductsAccess()
    {
        $this->json('GET', '/api/product')->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->json('POST', '/api/product')->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->json('GET', '/api/product/1')->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->json('PUT', '/api/product/1')->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->json('PATCH', '/api/product/1')->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->json('DELETE', '/api/product/1')->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testProductRequiredFields() {
        $response = $this->withHeaders($this->getApiHeaders())->postJson('/api/product', []);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $errors = $response->json('errors');
        $this->assertStringContainsStringIgnoringCase('required', $errors['name'][0]);
        $this->assertStringContainsStringIgnoringCase('required', $errors['description'][0]);
        $this->assertStringContainsStringIgnoringCase('required', $errors['price'][0]);
    }

    /**
     * Test GET /api/product
     */
    public function testProductList() {
        $products = $this->user->products();
        $this->withHeaders($this->getApiHeaders())
            ->getJson('/api/product')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount($products->count());
    }

    /**
     * Test POST /api/product
     */
    public function testProductPost() {
        $productData = $this->makeProductData();
        $response = $this->withHeaders($this->getApiHeaders())->postJson('/api/product', $productData);
        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('products', $productData);
    }

    /**
     * Test GET /api/product
     */
    public function testProductGet() {
        $product = $this->getRandomProduct();
        $response = $this->withHeaders($this->getApiHeaders())->getJson("/api/product/{$product->id}");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson($product->toArray());
    }

    /**
     * Test PUT /api/product
     */
    public function testProductPut() {
        $product = $this->getRandomProduct();
        $productData = $this->makeProductData();
        $response = $this->withHeaders($this->getApiHeaders())->putJson("/api/product/{$product->id}", $productData);
        $response->assertStatus(Response::HTTP_OK);

        $productData['id'] = $product->id;
        $this->assertDatabaseHas('products', $productData);
    }

    /**
     * Test PATCH /api/product
     */
    public function testProductPatch() {
        $product = $this->getRandomProduct();
        $productData = $this->makeProductData();
        $response = $this->withHeaders($this->getApiHeaders())->patchJson("/api/product/{$product->id}", $productData);
        $response->assertStatus(Response::HTTP_OK);

        $productData['id'] = $product->id;
        $this->assertDatabaseHas('products', $productData);
    }

    /**
     * Test DELETE /api/product
     */
    public function testProductDelete() {
        $product = $this->getRandomProduct();
        $response = $this->withHeaders($this->getApiHeaders())->deleteJson("/api/product/{$product->id}");
        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

}
