<?php

namespace App\Models;

use App\Services\TemplateRegistry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Portfolio extends Model
{
    use HasFactory;

    public const MODE_TEMPLATE = 'template';
    public const MODE_CODE = 'code';

    protected $fillable = [
        'user_id',
        'slug',
        'title',
        'description',
        'mode',
        'template',
        'accent',
        'content',
        'html',
        'css',
        'js',
        'is_published',
        'is_featured',
        'views',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
            'views' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dailyViews(): HasMany
    {
        return $this->hasMany(PortfolioView::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function isCode(): bool
    {
        return $this->mode === self::MODE_CODE;
    }

    public function isTemplate(): bool
    {
        return $this->mode === self::MODE_TEMPLATE;
    }

    public function publicUrl(): string
    {
        return route('portfolios.show', $this->slug);
    }

    public function previewUrl(): string
    {
        return route('studio.portfolios.preview', $this);
    }

    public function editUrl(): string
    {
        return $this->isCode()
            ? route('studio.portfolios.code.edit', $this)
            : route('studio.portfolios.content.edit', $this);
    }

    public function templateMeta(): array
    {
        return app(TemplateRegistry::class)->find($this->template);
    }

    /**
     * Always return a complete content shape, even for older records.
     *
     * @return array<string, mixed>
     */
    public function normalizedContent(): array
    {
        return app(TemplateRegistry::class)->normalizeContent(
            is_array($this->content) ? $this->content : [],
            $this->template,
            $this->accent,
        );
    }

    /** @return array<int, array<string, string>> */
    public function projects(): array
    {
        return $this->normalizedContent()['projects'];
    }

    public function projectCount(): int
    {
        return count(array_filter(
            $this->projects(),
            fn (array $project) => trim((string) ($project['title'] ?? '')) !== ''
        ));
    }

    /** @return array<string, int> */
    public function viewsSeries(int $days = 14): array
    {
        $start = now()->subDays($days - 1)->startOfDay();
        $found = $this->dailyViews()
            ->where('day', '>=', $start->toDateString())
            ->pluck('count', 'day')
            ->all();

        $series = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $day = now()->subDays($i)->toDateString();
            $series[$day] = (int) ($found[$day] ?? 0);
        }

        return $series;
    }

    public function coverUrl(): ?string
    {
        return portfolio_cover($this);
    }
}
