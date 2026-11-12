<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_person_can_register_and_lands_in_the_studio(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Ada Lovelace',
            'username' => 'ada_l',
            'email' => 'ada@example.test',
            'password' => 'a-long-password',
            'password_confirmation' => 'a-long-password',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('studio.portfolios.create'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'username' => 'ada_l',
            'email' => 'ada@example.test',
        ]);
    }

    public function test_usernames_and_emails_are_normalised_and_reserved_names_are_refused(): void
    {
        $response = $this->from(route('register'))->post(route('register.store'), [
            'name' => 'Sneaky Admin',
            'username' => 'admin',
            'email' => 'sneaky@example.test',
            'password' => 'a-long-password',
            'password_confirmation' => 'a-long-password',
            'terms' => '1',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_a_person_can_log_in_with_their_username(): void
    {
        $user = User::factory()->create([
            'username' => 'harbour',
            'email' => 'harbour@example.test',
            'password' => 'a-long-password',
        ]);

        $response = $this->post(route('login.store'), [
            'login' => 'HARBOUR',
            'password' => 'a-long-password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_bad_credentials_return_a_clear_error(): void
    {
        User::factory()->create([
            'username' => 'harbour',
            'email' => 'harbour@example.test',
            'password' => Hash::make('a-long-password'),
        ]);

        $response = $this->from(route('login'))->post(route('login.store'), [
            'login' => 'harbour',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    public function test_a_person_can_log_out(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }

    public function test_the_studio_requires_authentication(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->get(route('settings.edit'))->assertRedirect(route('login'));
    }
}
