<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\UserController
 */
final class UserControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $users = User::factory()->count(3)->create();

        $response = $this->get(route('users.index'));

        $response->assertOk();
        $response->assertViewIs('user.index');
        $response->assertViewHas('users', $users);
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('users.create'));

        $response->assertOk();
        $response->assertViewIs('user.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\UserController::class,
            'store',
            \App\Http\Requests\UserStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $name = fake()->name();
        $email = fake()->safeEmail();
        $password = fake()->password();
        $role = fake()->randomElement(/** enum_attributes **/);

        $response = $this->post(route('users.store'), [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
        ]);

        $users = User::query()
            ->where('name', $name)
            ->where('email', $email)
            ->where('password', $password)
            ->where('role', $role)
            ->get();
        $this->assertCount(1, $users);
        $user = $users->first();

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('user.id', $user->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $user = User::factory()->create();

        $response = $this->get(route('users.show', $user));

        $response->assertOk();
        $response->assertViewIs('user.show');
        $response->assertViewHas('user', $user);
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $user = User::factory()->create();

        $response = $this->get(route('users.edit', $user));

        $response->assertOk();
        $response->assertViewIs('user.edit');
        $response->assertViewHas('user', $user);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\UserController::class,
            'update',
            \App\Http\Requests\UserUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $user = User::factory()->create();
        $name = fake()->name();
        $email = fake()->safeEmail();
        $password = fake()->password();
        $role = fake()->randomElement(/** enum_attributes **/);

        $response = $this->put(route('users.update', $user), [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
        ]);

        $user->refresh();

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('user.id', $user->id);

        $this->assertEquals($name, $user->name);
        $this->assertEquals($email, $user->email);
        $this->assertEquals($password, $user->password);
        $this->assertEquals($role, $user->role);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $user = User::factory()->create();

        $response = $this->delete(route('users.destroy', $user));

        $response->assertRedirect(route('users.index'));

        $this->assertModelMissing($user);
    }
}
