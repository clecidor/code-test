<?php

namespace Tests\Feature;

use App\Product;
use App\User;
use \Symfony\Component\HttpFoundation\Response;

class UserProductApiTest extends ApiTestCase
{
    /**
     * /api/user/product not accessible to unauthorized users.
     *
     * @return void
     */
    public function testUnauthorizedProductsAccess()
    {
        $this->json('POST', '/api/user/product')->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
        $this->json('GET', '/api/user/product')->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->json('GET', '/api/user/product/1')->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->json('PUT', '/api/user/product/1')->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->json('PATCH', '/api/user/product/1')->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
        $this->json('DELETE', '/api/user/product/1')->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Test GET /api/user/product
     */
    public function testUserProductList() {
        $response = $this->withHeaders($this->getApiHeaders())->getJson('/api/user/product');
        $response->assertStatus(Response::HTTP_OK);

        $userProducts = $this->user->load(['products'])->toArray();
        $response->assertJson($userProducts);
    }

    /**
     * Test GET /api/user/product/{product}
     */
    public function testUserProductGet() {
        $product = $this->getRandomProduct();
        $response = $this->withHeaders($this->getApiHeaders())->getJson("/api/user/product/{$product->id}");
        $response->assertStatus(Response::HTTP_OK);
        $productWithUser = $product->load(['user'])->toArray();
        $response->assertJson($productWithUser);
    }

    /**
     * Test PUT /api/user/product/{product}
     */
    public function testUserProductPutAssociate() {
        $product = factory(Product::class)->create();
        $this->assertDatabaseHas('products', ['id' => $product->id ]);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
            'user_id' => $this->user->id,
        ]);

        $response = $this->withHeaders($this->getApiHeaders())->putJson("/api/user/product/{$product->id}");
        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'user_id' => $this->user->id,
        ]);
    }

    /**
     * Test DELETE /api/user/product/{product}
     */
    public function testUserProductDeleteDissociate() {
        $product = $this->getRandomProduct();
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'user_id' => $this->user->id,
        ]);

        $response = $this->withHeaders($this->getApiHeaders())->deleteJson("/api/user/product/{$product->id}");
        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('products', ['id' => $product->id ]);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
            'user_id' => $this->user->id,
        ]);
    }
}
