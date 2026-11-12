<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class BrandMark extends Component
{
    public function __construct(
        public int $size = 26,
        public string $class = 'brand__mark',
    ) {
    }

    public function render(): View
    {
        return view('components.brand-mark');
    }
}
