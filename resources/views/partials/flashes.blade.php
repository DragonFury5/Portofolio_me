@if (session('status') || session('error'))
    <div class="wrap flash-stack">
        @if (session('status'))
            <div class="flash flash--success" role="status">
                <span class="flash__icon"><x-icon name="check" :size="16" /></span>
                <p>{{ session('status') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="flash flash--error" role="alert">
                <span class="flash__icon"><x-icon name="x" :size="16" /></span>
                <p>{{ session('error') }}</p>
            </div>
        @endif
    </div>
@endif
