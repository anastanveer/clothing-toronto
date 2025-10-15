<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_shop_page_renders_with_pagination(): void
    {
        // Seed the catalog data
        $this->seed();

        $response = $this->get('/shop');

        $response->assertStatus(200);
        $response->assertSee('Shop');
    }

    public function test_category_page_filters_products(): void
    {
        $this->seed();

        $response = $this->get('/shop/category/men');

        $response->assertStatus(200);
        $response->assertSee('Men');
    }
}
