<?php

namespace Database\Factories;

use App\Models\Portfolio;
use App\Models\User;
use App\Services\TemplateRegistry;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Portfolio>
 */
class PortfolioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'slug' => Str::lower(fake()->unique()->word()).'-'.Str::lower(Str::random(6)),
            'title' => fake()->sentence(3),
            'description' => fake()->sentence(14),
            'mode' => Portfolio::MODE_TEMPLATE,
            'template' => 'ledger',
            'accent' => 'blue',
            'content' => app(TemplateRegistry::class)->starterContent('ledger'),
            'html' => '',
            'css' => '',
            'js' => '',
            'is_published' => false,
            'is_featured' => false,
            'views' => 0,
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    public function code(bool $withStarter = true): static
    {
        $starter = app(\App\Services\CodeStarter::class)->find('blank');

        return $this->state(fn () => [
            'mode' => Portfolio::MODE_CODE,
            'template' => null,
            'content' => [],
            'html' => $withStarter ? $starter['html'] : '',
            'css' => $withStarter ? $starter['css'] : '',
            'js' => $withStarter ? $starter['js'] : '',
        ]);
    }
}
