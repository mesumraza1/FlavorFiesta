<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

class ControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_controller_uses_authorizes_requests_trait()
    {
        $controller = $this->getMockForAbstractClass(Controller::class);

        $this->assertContains('AuthorizesRequests', class_uses($controller));
    }

    public function test_controller_uses_validates_requests_trait()
    {
        $controller = $this->getMockForAbstractClass(Controller::class);

        $this->assertContains('ValidatesRequests', class_uses($controller));
    }
}
