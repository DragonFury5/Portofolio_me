<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioView extends Model
{
    protected $fillable = [
        'portfolio_id',
        'day',
        'count',
    ];

    protected function casts(): array
    {
        return [
            'day' => 'date',
            'count' => 'integer',
        ];
    }

    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }
}
