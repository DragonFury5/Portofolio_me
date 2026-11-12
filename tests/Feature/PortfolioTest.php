<?php

namespace Tests\Feature;

use App\Models\Portfolio;
use App\Models\User;
use App\Services\TemplateRegistry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_template_page_is_created_as_a_private_draft(): void
    {
        $user = User::factory()->create(['username' => 'editor']);
        $this->actingAs($user);

        $response = $this->post(route('studio.portfolios.store'), [
            'mode' => 'template',
            'template' => 'atlas',
        ]);

        $portfolio = Portfolio::firstOrFail();

        $response->assertRedirect(route('studio.portfolios.content.edit', $portfolio));
        $this->assertFalse($portfolio->is_published);
        $this->assertSame('atlas', $portfolio->template);
        $this->assertSame($user->id, $portfolio->user_id);
        $this->assertNotEmpty($portfolio->content['projects']);
        $this->assertSame('editor', $portfolio->slug);
    }

    public function test_a_code_page_gets_a_real_starter_and_opens_the_editor(): void
    {
        $user = User::factory()->create(['username' => 'motionmaker']);
        $this->actingAs($user);

        $response = $this->post(route('studio.portfolios.store'), [
            'mode' => 'code',
            'starter' => 'kinetic',
        ]);

        $portfolio = Portfolio::firstOrFail();

        $response->assertRedirect(route('studio.portfolios.code.edit', $portfolio));
        $this->assertSame(Portfolio::MODE_CODE, $portfolio->mode);
        $this->assertStringContainsString('MOTION', $portfolio->html);
        $this->assertStringContainsString('pointermove', $portfolio->js);
    }

    public function test_a_person_cannot_create_more_than_four_pages(): void
    {
        $user = User::factory()->create();
        Portfolio::factory()->count(4)->for($user)->create();
        $this->actingAs($user);

        $response = $this->post(route('studio.portfolios.store'), [
            'mode' => 'template',
            'template' => 'ledger',
        ]);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
        $this->assertSame(4, $user->portfolios()->count());
    }

    public function test_content_can_autosave_with_nested_projects_and_links(): void
    {
        $user = User::factory()->create();
        $portfolio = Portfolio::factory()->for($user)->create(['template' => 'signal']);
        $this->actingAs($user);

        $response = $this->putJson(route('studio.portfolios.content.update', $portfolio), [
            'eyebrow' => 'Engineer · Jakarta',
            'headline' => 'Small systems that *hold*.',
            'intro' => 'A new introduction.',
            'about' => 'A longer story about the practice.',
            'location' => 'Jakarta, ID',
            'email' => 'hello@example.test',
            'availability' => 'Open in May',
            'links' => [
                ['label' => 'GitHub', 'url' => 'https://github.com/example'],
                ['label' => 'Broken', 'url' => 'javascript:alert(1)'],
            ],
            'projects' => [
                ['title' => 'First project', 'year' => '2026', 'role' => 'Design', 'summary' => 'One.', 'image' => '', 'url' => ''],
                ['title' => '', 'year' => '2024', 'role' => 'Skipped', 'summary' => '', 'image' => '', 'url' => ''],
                ['title' => 'Second project', 'year' => '2025', 'role' => 'Code', 'summary' => 'Two.', 'image' => '', 'url' => 'https://example.com/work'],
            ],
        ]);

        $response->assertOk()->assertJson(['ok' => true]);

        $portfolio->refresh();
        $this->assertSame('Small systems that *hold*.', $portfolio->content['headline']);
        $this->assertCount(2, $portfolio->content['projects']);
        $this->assertSame('First project', $portfolio->content['projects'][0]['title']);
        $this->assertSame('Second project', $portfolio->content['projects'][1]['title']);
        $this->assertCount(2, $portfolio->content['links']);
    }

    public function test_a_page_owner_can_publish_and_the_public_can_see_it(): void
    {
        $user = User::factory()->create();
        $portfolio = Portfolio::factory()->for($user)->create(['slug' => 'public-page']);
        $this->actingAs($user);

        $this->post(route('studio.portfolios.publish', $portfolio), ['published' => '1'])
            ->assertRedirect();

        $this->assertTrue($portfolio->fresh()->is_published);

        $response = $this->get(route('portfolios.show', 'public-page'));
        $response->assertOk();
        $response->assertSee('data-page-template', false);
    }

    public function test_an_unpublished_page_is_not_public(): void
    {
        Portfolio::factory()->create(['slug' => 'private-page']);

        $this->get(route('portfolios.show', 'private-page'))->assertNotFound();
    }

    public function test_a_person_cannot_open_or_change_someone_elses_editor(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $portfolio = Portfolio::factory()->for($owner)->create();

        $this->actingAs($other)
            ->get(route('studio.portfolios.content.edit', $portfolio))
            ->assertNotFound();

        $this->actingAs($other)
            ->putJson(route('studio.portfolios.content.update', $portfolio), [
                'headline' => 'Stolen',
                'projects' => [],
                'links' => [],
            ])
            ->assertNotFound();

        $this->assertNotSame('Stolen', $portfolio->fresh()->content['headline'] ?? null);
    }

    public function test_template_settings_can_change_slug_template_and_accent(): void
    {
        $user = User::factory()->create();
        $portfolio = Portfolio::factory()->for($user)->create([
            'slug' => 'old-address',
            'template' => 'ledger',
        ]);
        $this->actingAs($user);

        $response = $this->put(route('studio.portfolios.settings.update', $portfolio), [
            'title' => 'A better title',
            'slug' => 'new-address',
            'description' => 'A clearer description.',
            'template' => 'tide',
            'accent' => 'cyan',
        ]);

        $response->assertRedirect(route('studio.portfolios.settings.edit', $portfolio));

        $portfolio->refresh();
        $this->assertSame('new-address', $portfolio->slug);
        $this->assertSame('tide', $portfolio->template);
        $this->assertSame('cyan', $portfolio->accent);
        $this->assertNotEmpty($portfolio->content['projects']);
    }

    public function test_reserved_slugs_are_rejected(): void
    {
        $user = User::factory()->create();
        $portfolio = Portfolio::factory()->for($user)->create();
        $this->actingAs($user);

        $this->from(route('studio.portfolios.settings.edit', $portfolio))
            ->put(route('studio.portfolios.settings.update', $portfolio), [
                'title' => 'Title',
                'slug' => 'admin',
                'description' => '',
                'template' => 'ledger',
                'accent' => 'blue',
            ])
            ->assertSessionHasErrors('slug');
    }

    public function test_deleting_a_page_requires_the_exact_address(): void
    {
        $user = User::factory()->create();
        $portfolio = Portfolio::factory()->for($user)->create(['slug' => 'keep-me']);
        $this->actingAs($user);

        $this->from(route('studio.portfolios.settings.edit', $portfolio))
            ->delete(route('studio.portfolios.destroy', $portfolio), ['confirm' => 'wrong'])
            ->assertSessionHasErrors('confirm');

        $this->assertDatabaseHas('portfolios', ['id' => $portfolio->id]);

        $this->delete(route('studio.portfolios.destroy', $portfolio), ['confirm' => 'keep-me'])
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseMissing('portfolios', ['id' => $portfolio->id]);
    }

    public function test_a_person_can_ask_for_a_different_template_at_creation_via_the_picker(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->get(route('studio.portfolios.create', ['template' => 'tide']))
            ->assertOk()
            ->assertSee('name="template"', false)
            ->assertSee('value="tide"', false);
    }
}
