<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class MakerController extends Controller
{
    public function show(string $username): View
    {
        $maker = User::query()
            ->where('username', $username)
            ->with(['publishedPortfolios' => fn ($query) => $query->orderByDesc('updated_at')])
            ->firstOrFail();

        return view('pages.maker', [
            'maker' => $maker,
            'portfolios' => $maker->publishedPortfolios,
        ]);
    }
}
