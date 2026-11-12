<section class="error-page">
    <div class="wrap error-grid">
        <div>
            <p class="eyebrow">Error {{ $code }}</p>
            <div class="error-code">{{ $code }}</div>
            <h1>{{ $heading }}</h1>
            <p>{{ $body }}</p>
            <div class="error-links">
                <a class="btn btn--primary" href="{{ route('home') }}">Back to portside <x-icon name="arrow-right" :size="16" /></a>
                <a class="btn btn--outline" href="{{ route('explore') }}">Explore the work</a>
            </div>
        </div>
        <div class="error-art" aria-hidden="true">
            <svg viewBox="0 0 520 380" fill="none">
                <rect x="48" y="44" width="424" height="292" rx="10" stroke="#c7bda7" stroke-width="1.5"/>
                <path d="M48 82h424" stroke="#c7bda7" stroke-width="1.5"/>
                <circle cx="70" cy="63" r="4" fill="#60C1E5"/>
                <circle cx="86" cy="63" r="4" fill="#A0E0EF"/>
                <circle cx="102" cy="63" r="4" fill="#1B365D" opacity=".35"/>
                <path d="M92 151c34 5 49 27 63 58s44 44 74 38 50-31 81-35 53 16 74 11" stroke="#005A9C" stroke-width="2" stroke-linecap="round"/>
                <path d="M92 181c30 4 45 24 58 51s42 38 69 33 45-28 73-31 48 14 67 10" stroke="#60C1E5" stroke-width="1.5" stroke-linecap="round" opacity=".75"/>
                <path d="M92 211c27 4 39 20 51 43s38 32 61 28 40-24 64-27 42 12 59 8" stroke="#A0E0EF" stroke-width="1.5" stroke-linecap="round" opacity=".5"/>
                <circle cx="375" cy="150" r="42" stroke="#1B365D" stroke-width="1.2" opacity=".35"/>
                <path d="M375 105v90M330 150h90" stroke="#1B365D" stroke-width="1" opacity=".22"/>
                <path d="M149 267l44-29 31 34 26-18 41 31" stroke="#1B365D" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="158" cy="256" r="3" fill="#005A9C"/>
                <path d="M52 336c32-5 48 9 76 5s51-13 82-6 45 11 75 4 50-8 80 0" stroke="#60C1E5" stroke-width="2" stroke-linecap="round" opacity=".6"/>
            </svg>
        </div>
    </div>
</section>
