<?php

namespace Tests\Feature;

use App\Models\Portfolio;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StudioExtrasTest extends TestCase
{
    use RefreshDatabase;

    public function test_images_are_reencoded_and_only_raster_formats_are_allowed(): void
    {
        Storage::fake('uploads');

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson(route('studio.uploads.store'), [
            'file' => UploadedFile::fake()->image('project.jpg', 2000, 1200),
            'kind' => 'project',
        ]);

        $response->assertOk()->assertJsonPath('ok', true);
        $path = $response->json('path');
        $this->assertStringStartsWith('uploads/'.$user->id.'/', $path);
        $this->assertStringEndsWith('.jpg', $path);
        $this->assertCount(1, Storage::disk('uploads')->allFiles());

        $this->postJson(route('studio.uploads.store'), [
            'file' => UploadedFile::fake()->create('vector.svg', 8, 'image/svg+xml'),
        ])->assertUnprocessable();
    }

    public function test_profile_updates_normalise_the_website_and_can_change_the_avatar(): void
    {
        Storage::fake('uploads');

        $user = User::factory()->create([
            'username' => 'old_username',
            'password' => 'a-long-password',
        ]);
        $this->actingAs($user);

        $response = $this->put(route('settings.profile'), [
            'name' => 'New Name',
            'username' => 'new_username',
            'bio' => 'A short new bio.',
            'location' => 'Bandung',
            'website' => 'example.com/portfolio',
            'avatar' => UploadedFile::fake()->image('portrait.png', 500, 500),
        ]);

        $response->assertRedirect(route('settings.edit'));

        $user->refresh();
        $this->assertSame('New Name', $user->name);
        $this->assertSame('new_username', $user->username);
        $this->assertSame('https://example.com/portfolio', $user->website);
        $this->assertNotNull($user->avatar);
        $this->assertStringStartsWith('uploads/', $user->avatar);
    }

    public function test_a_person_can_change_their_password_with_the_current_one(): void
    {
        $user = User::factory()->create(['password' => 'old-password']);
        $this->actingAs($user);

        $this->put(route('settings.password'), [
            'current_password' => 'wrong',
            'password' => 'brand-new-password',
            'password_confirmation' => 'brand-new-password',
        ])->assertSessionHasErrors('current_password');

        $this->put(route('settings.password'), [
            'current_password' => 'old-password',
            'password' => 'brand-new-password',
            'password_confirmation' => 'brand-new-password',
        ])->assertRedirect(route('settings.edit'));

        $this->assertTrue(Hash::check('brand-new-password', $user->fresh()->password));
    }

    public function test_reports_can_be_filed_and_resolved_by_staff(): void
    {
        $owner = User::factory()->create();
        $admin = User::factory()->admin()->create();
        $portfolio = Portfolio::factory()->for($owner)->published()->create(['slug' => 'reported-page']);

        $this->get(route('reports.create', $portfolio->slug))->assertOk()->assertSee('Flag this page.');

        $this->post(route('reports.store', $portfolio->slug), [
            'reason' => 'stolen-work',
            'details' => 'This looks copied from another maker.',
            'reporter_email' => 'reporter@example.test',
        ])->assertRedirect(route('portfolios.show', $portfolio->slug));

        $report = Report::firstOrFail();
        $this->assertSame('open', $report->status);

        $this->actingAs($owner)
            ->get(route('admin.index'))
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('admin.index'))
            ->assertOk()
            ->assertSee('This looks copied');

        $this->actingAs($admin)
            ->post(route('admin.reports.resolve', $report))
            ->assertRedirect();

        $this->assertSame('resolved', $report->fresh()->status);
        $this->assertNotNull($report->fresh()->resolved_at);
    }

    public function test_staff_can_feature_and_unpublish_a_page(): void
    {
        $admin = User::factory()->admin()->create();
        $portfolio = Portfolio::factory()->published()->create(['slug' => 'staff-page']);

        $this->actingAs($admin)
            ->post(route('admin.pages.feature', $portfolio))
            ->assertRedirect();

        $this->assertTrue($portfolio->fresh()->is_featured);

        $this->actingAs($admin)
            ->post(route('admin.pages.unpublish', $portfolio))
            ->assertRedirect();

        $this->assertFalse($portfolio->fresh()->is_published);
    }

    public function test_fonts_for_sandboxed_pages_are_served_with_cors(): void
    {
        $response = $this->get(route('fonts.show', 'fraunces-latin-normal-400-700.woff2'));

        $response->assertOk();
        $response->assertHeader('Access-Control-Allow-Origin', '*');
        $response->assertHeader('Content-Type', 'font/woff2');

        $this->get(route('fonts.show', 'not-a-font.woff2'))->assertNotFound();
    }

    public function test_page_titles_are_escaped_inside_the_studio_shell(): void
    {
        $user = User::factory()->create();
        $portfolio = Portfolio::factory()->for($user)->create([
            'title' => '</title><script>alert("studio")</script>',
        ]);

        $response = $this->actingAs($user)->get(route('studio.portfolios.content.edit', $portfolio));

        $response->assertOk();
        $response->assertDontSee('<script>alert("studio")</script>', false);
        $response->assertSee('&lt;/title&gt;', false);
    }

    public function test_the_custom_404_page_is_used(): void
    {
        $this->get('/this-page-is-not-here')
            ->assertNotFound()
            ->assertSee('That page slipped the mooring.');
    }
}
