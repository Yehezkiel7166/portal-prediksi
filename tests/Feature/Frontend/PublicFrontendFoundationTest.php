<?php

namespace Tests\Feature\Frontend;

use App\Http\Controllers\Frontend\HomeController;
use Tests\TestCase;

final class PublicFrontendFoundationTest extends TestCase
{
    public function test_homepage_uses_the_frontend_controller_and_layout(): void
    {
        $response = $this->get(route('home'));

        $response
            ->assertOk()
            ->assertViewIs('frontend.home')
            ->assertSee(config('app.name'))
            ->assertSee('Live Draw')
            ->assertSee('Prediksi Togel')
            ->assertSee('Slot Gacor')
            ->assertSee('Alat Togel')
            ->assertSee('Data Result');

        $this->assertSame(
            HomeController::class,
            app('router')->getRoutes()->getByName('home')->getActionName(),
        );
    }
}
