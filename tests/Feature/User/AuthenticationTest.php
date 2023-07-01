<?php

namespace Tests\Feature\User;

use Domain\User\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use DatabaseMigrations;

    public function test_user_can_register(): void
    {
        $params = [
            'name' => 'John',
            'email' => 'john@doe.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ];

        $this->postJson(route('api.auth.register'), $params)
            ->assertStatus(201);
    }

    public function test_user_cannot_register_new_account_account_exists(): void
    {
        $params = [
            'name' => 'John',
            'email' => 'john@doe.com',
            'password' => '12345678',
        ];

        User::factory()->create($params);

        $this->postJson(route('api.auth.register'), $params)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_user_cannot_register_new_account_missing_password(): void
    {
        $params = [
        ];

        $this->postJson(route('api.auth.register'), $params)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password', 'password_confirmation']);
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create(['password' => 'abcdefg']);

        $this->postJson(route('api.auth.login'), ['email' => $user->email, 'password' => 'abcdefg'])
            ->assertStatus(200);
    }

    public function test_user_cannot_login_invalid_credentials(): void
    {
        $user = User::factory()->create(['password' => 'abcdefg']);

        $this->postJson(route('api.auth.login'), ['email' => $user->email, 'password' => 'wrongpassword'])
            ->assertStatus(401);
    }

    public function test_user_cannot_login_missing_credentials(): void
    {
        $this->postJson(route('api.auth.login'), [])
            ->assertStatus(422);
    }

    public function test_user_can_logout_successful(): void
    {
        $user = User::factory()->create(['password' => 'abcdefg']);

        $this->postJson(route('api.auth.login'), ['email' => $user->email, 'password' => 'abcdefg'])
            ->assertStatus(200);

        $this->assertEquals(1, $user->tokens()->count());

        $this->postJson(route('api.auth.logout'));

        $this->assertEquals(0, $user->tokens()->count());
    }

}
