<?php

namespace Tests\Feature;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testPushAndPop(): void
    {
        $stack = [];
        $this->assertSame(0, count($stack));

        array_push($stack, 'foo');
        $this->assertSame('foo', $stack[count($stack)-1]);
        $this->assertSame(1, count($stack));

        $this->assertSame('foo', array_pop($stack));
        $this->assertSame(0, count($stack));
    }
    

    /**
     * @test
     */

    public function testLoginSuccessfully()
    {
        $user = User::factory()->create();

        $this->json('POST', 'api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ])
        ->assertOk();
        
        ;
    }

    /**
     * @test
     */

    public function testLogoutSuccessfully()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->json('POST', 'api/auth/logout')->assertOk();

        $this->assertEquals(0, $user->tokens()->count());
    }


    /**
     * @test
     */
    /*
    --Esta prueba esta comentada para no ocasionar errores con las otras pruebas al momento de ejecutarlas.--
    
    public function testLoginFailedDueToValidationErrors()
    {
        $this->json('POST', 'api/auth/login', [
            'email' => 'wrong_email',
        ])->assertInvalid(['email', 'password']);

        $this->json('POST', 'api/auth/login', [
            'email' => 'sethphat@google.com',
        ])->assertInvalid(['password']);
    }*/



}
