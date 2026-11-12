@props(['user' => null, 'size' => 32, 'name' => null])

@php
    $displayName = $name ?: ($user?->name ?? 'Portside');
    $source = $user?->avatar ?? null;
    $tone = $user ? (abs(crc32((string) $user->username)) % 4) + 1 : 1;
@endphp

@if ($source)
    <img
        class="avatar"
        src="{{ media_url($source) }}"
        width="{{ (int) $size }}"
        height="{{ (int) $size }}"
        alt="{{ $displayName }}"
        loading="lazy"
        decoding="async"
        style="--avatar-size:{{ (int) $size }}px"
    >
@else
    <span class="avatar avatar--tone-{{ $tone }}" aria-hidden="true" style="--avatar-size:{{ (int) $size }}px">{{ initials($displayName) }}</span>
@endif
