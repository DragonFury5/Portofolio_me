<?php

namespace Tests\Feature;

use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CodeStudioTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_public_code_page_is_served_inside_a_sandbox(): void
    {
        $portfolio = Portfolio::factory()->code()->published()->create([
            'slug' => 'kinetic-page',
            'html' => '<main><h1>Hello from code</h1></main>',
            'css' => 'body{background:#123}',
            'js' => 'document.body.dataset.ready="yes";',
        ]);

        $response = $this->get(route('portfolios.show', $portfolio->slug));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $response->assertSee('Hello from code', false);
        $response->assertSee('document.body.dataset.ready', false);
        $response->assertSee('assets/css/code-fonts.css', false);

        $csp = $response->headers->get('Content-Security-Policy');
        $this->assertStringContainsString('sandbox allow-scripts', $csp);
        $this->assertStringNotContainsString('allow-same-origin', $csp);
        $this->assertStringContainsString("frame-ancestors 'self'", $csp);
    }

    public function test_code_pages_are_saved_with_limits_and_autosave_responses(): void
    {
        $user = User::factory()->create();
        $portfolio = Portfolio::factory()->code()->for($user)->create();
        $this->actingAs($user);

        $response = $this->putJson(route('studio.portfolios.code.update', $portfolio), [
            'html' => '<main><h1>Updated</h1></main>',
            'css' => 'h1{color:red}',
            'js' => 'console.log("harbour")',
        ]);

        $response->assertOk()->assertJson(['ok' => true]);
        $portfolio->refresh();
        $this->assertSame('<main><h1>Updated</h1></main>', $portfolio->html);
        $this->assertSame('h1{color:red}', $portfolio->css);

        $tooLarge = $this->putJson(route('studio.portfolios.code.update', $portfolio), [
            'html' => str_repeat('a', 300_001),
            'css' => '',
            'js' => '',
        ]);

        $tooLarge->assertUnprocessable();
        $this->assertNotSame(str_repeat('a', 10), $portfolio->fresh()->html);
    }

    public function test_only_the_owner_can_preview_or_export_a_code_page(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $portfolio = Portfolio::factory()->code()->for($owner)->create(['slug' => 'export-me']);

        $this->actingAs($owner)
            ->get(route('studio.portfolios.preview', $portfolio))
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertSee('<!doctype html>', false);

        $download = $this->actingAs($owner)->get(route('studio.portfolios.export', $portfolio));
        $download->assertOk();
        $this->assertStringContainsString('attachment; filename="export-me.html"', $download->headers->get('Content-Disposition'));

        $this->actingAs($other)
            ->get(route('studio.portfolios.export', $portfolio))
            ->assertNotFound();

        $this->actingAs($other)
            ->get(route('studio.portfolios.code.edit', $portfolio))
            ->assertNotFound();
    }

    public function test_template_pages_do_not_get_the_public_code_sandbox(): void
    {
        $portfolio = Portfolio::factory()->published()->create(['slug' => 'template-page']);

        $response = $this->get(route('portfolios.show', $portfolio->slug));

        $response->assertOk();
        $this->assertNull($response->headers->get('Content-Security-Policy'));
    }
}
