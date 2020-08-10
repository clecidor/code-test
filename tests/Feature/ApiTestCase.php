<?php


namespace Tests\Feature;

use App\Product;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

abstract class ApiTestCase extends TestCase
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

    /**
     * @return string[]
     */
    protected function getApiHeaders () {
        return [
            'Authorization' => "Bearer {$this->user->api_token}"
        ];
    }

    protected function getRandomProduct() {
        return $this->user->products()->inRandomOrder()->first();
    }
}
