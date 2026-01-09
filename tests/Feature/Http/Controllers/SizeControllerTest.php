<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Size;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\SizeController
 */
final class SizeControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $sizes = Size::factory()->count(3)->create();

        $response = $this->get(route('sizes.index'));

        $response->assertOk();
        $response->assertViewIs('size.index');
        $response->assertViewHas('sizes', $sizes);
    }

    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('sizes.create'));

        $response->assertOk();
        $response->assertViewIs('size.create');
    }

    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\SizeController::class,
            'store',
            \App\Http\Requests\SizeStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $size = fake()->word();

        $response = $this->post(route('sizes.store'), [
            'size' => $size,
        ]);

        $sizes = Size::query()
            ->where('size', $size)
            ->get();
        $this->assertCount(1, $sizes);
        $size = $sizes->first();

        $response->assertRedirect(route('sizes.index'));
        $response->assertSessionHas('size.id', $size->id);
    }

    #[Test]
    public function show_displays_view(): void
    {
        $size = Size::factory()->create();

        $response = $this->get(route('sizes.show', $size));

        $response->assertOk();
        $response->assertViewIs('size.show');
        $response->assertViewHas('size', $size);
    }

    #[Test]
    public function edit_displays_view(): void
    {
        $size = Size::factory()->create();

        $response = $this->get(route('sizes.edit', $size));

        $response->assertOk();
        $response->assertViewIs('size.edit');
        $response->assertViewHas('size', $size);
    }

    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\SizeController::class,
            'update',
            \App\Http\Requests\SizeUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $size = Size::factory()->create();
        $size = fake()->word();

        $response = $this->put(route('sizes.update', $size), [
            'size' => $size,
        ]);

        $size->refresh();

        $response->assertRedirect(route('sizes.index'));
        $response->assertSessionHas('size.id', $size->id);

        $this->assertEquals($size, $size->size);
    }

    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $size = Size::factory()->create();

        $response = $this->delete(route('sizes.destroy', $size));

        $response->assertRedirect(route('sizes.index'));

        $this->assertModelMissing($size);
    }
}
