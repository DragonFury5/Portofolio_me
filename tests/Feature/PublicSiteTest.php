<?php

namespace Tests\Feature;

use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_public_pages_render(): void
    {
        $this->get(route('home'))->assertOk()->assertSee('Your work,')->assertSee('at ')
            ->assertSee('data-hero-cinema', false)
            ->assertSee('assets/js/motion.js', false);
        $this->get(route('explore'))->assertOk()->assertSee('Work worth');
        $this->get(route('templates.index'))->assertOk()->assertSee('Templates that don');
        $this->get(route('templates.preview', 'ledger'))->assertOk()->assertSee('Ledger');
        $this->get(route('docs.code'))->assertOk()->assertSee('The code guide.');
        $this->get(route('terms'))->assertOk()->assertSee('House rules.');
        $this->get(route('privacy'))->assertOk()->assertSee('Privacy, plainly.');
        $this->get(route('login'))->assertOk()->assertSee('Welcome back.');
        $this->get(route('register'))->assertOk()->assertSee('Start a portfolio.');
        $this->get(route('robots'))->assertOk()->assertSee('Sitemap:');
    }

    public function test_a_template_escapes_content_and_strips_dangerous_links(): void
    {
        $user = User::factory()->create(['name' => 'A <script>User</script>']);
        $portfolio = Portfolio::factory()->for($user)->published()->create([
            'slug' => 'escape-test',
            'template' => 'ledger',
            'content' => [
                'eyebrow' => 'Designer',
                'headline' => 'Hello <script>alert(1)</script>',
                'intro' => 'A short intro.',
                'about' => 'About.',
                'location' => 'Jakarta',
                'email' => 'hello@example.test',
                'availability' => '',
                'links' => [
                    ['label' => 'Danger', 'url' => 'javascript:alert(2)'],
                    ['label' => 'Safe', 'url' => 'https://example.com'],
                ],
                'projects' => [
                    [
                        'title' => '<b>Not bold</b>',
                        'year' => '2026',
                        'role' => 'Role',
                        'summary' => '',
                        'image' => '',
                        'url' => 'javascript:alert(3)',
                    ],
                ],
            ],
        ]);

        $response = $this->get(route('portfolios.show', $portfolio->slug));

        $response->assertOk();
        $response->assertDontSee('<script>alert(1)</script>', false);
        $response->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
        $response->assertDontSee('href="javascript:', false);
        $response->assertSee('href="https://example.com"', false);
        $response->assertSee('A &lt;script&gt;User&lt;/script&gt;', false);
    }

    public function test_the_maker_profile_only_lists_published_pages(): void
    {
        $user = User::factory()->create(['username' => 'visible-maker', 'name' => 'Visible Maker']);
        $published = Portfolio::factory()->for($user)->published()->create([
            'slug' => 'visible-work',
            'title' => 'Published work',
        ]);
        $draft = Portfolio::factory()->for($user)->create([
            'slug' => 'private-work',
            'title' => 'Unpublished work',
        ]);

        $response = $this->get(route('makers.show', 'visible-maker'));

        $response->assertOk();
        $response->assertSee('Published work');
        $response->assertDontSee('Unpublished work');
    }

    public function test_views_count_once_per_session_and_not_for_embeds(): void
    {
        $portfolio = Portfolio::factory()->published()->create(['slug' => 'counted-page']);

        $this->get(route('portfolios.show', $portfolio->slug))->assertOk();
        $this->assertSame(1, $portfolio->fresh()->views);

        $this->get(route('portfolios.show', $portfolio->slug))->assertOk();
        $this->assertSame(1, $portfolio->fresh()->views);

        $this->get(route('portfolios.show', ['slug' => $portfolio->slug, 'embed' => 1]))->assertOk();
        $this->assertSame(1, $portfolio->fresh()->views);

        $this->assertDatabaseHas('portfolio_views', [
            'portfolio_id' => $portfolio->id,
            'day' => now()->toDateString(),
            'count' => 1,
        ]);
    }

    public function test_the_sitemap_contains_published_pages_only(): void
    {
        $user = User::factory()->create(['username' => 'sitemap-maker']);
        Portfolio::factory()->for($user)->published()->create(['slug' => 'in-the-sitemap']);
        Portfolio::factory()->for($user)->create(['slug' => 'not-in-the-sitemap']);

        $response = $this->get(route('sitemap'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $response->assertSee('in-the-sitemap');
        $response->assertDontSee('not-in-the-sitemap');
        $response->assertSee('sitemap-maker');
    }

    public function test_explore_can_be_searched_and_filtered(): void
    {
        $user = User::factory()->create(['name' => 'Mara Searchable']);
        Portfolio::factory()->for($user)->published()->create([
            'slug' => 'mara-search',
            'title' => 'Mara Search',
            'mode' => 'template',
            'template' => 'ledger',
        ]);
        Portfolio::factory()->published()->code()->create([
            'title' => 'A kinetic experiment',
            'slug' => 'kinetic-search',
        ]);

        $this->get(route('explore', ['q' => 'Mara Searchable']))
            ->assertOk()
            ->assertSee('Mara Search')
            ->assertDontSee('A kinetic experiment');

        $this->get(route('explore', ['mode' => 'code']))
            ->assertOk()
            ->assertSee('A kinetic experiment')
            ->assertDontSee('Mara Search');
    }
}
