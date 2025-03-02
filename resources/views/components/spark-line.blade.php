@props(['values' => array_map(fn() => rand(1, 100), range(1, 10)), 'width' => 150, 'height' => 30, 'padding' => 2])

@php
    $min = min($values);
    $max = max($values);
    $range = $max - $min ?: 1; // Avoid division by zero if all values are the same
    $widthStep = count($values) > 1 ? ($width - $padding * 2) / (count($values) - 1) : $width;

    $points = collect($values)
        ->map(fn($v, $i) =>
            ($i * $widthStep + $padding) . ',' . ($height - $padding - (($v - $min) / $range) * ($height - $padding * 2))
        )
        ->implode(' ');
@endphp

<svg width="{{ $width }}" height="{{ $height }}" viewBox="0 0 {{ $width }} {{ $height }}" class="border border-gray-300 rounded {{ $attributes->get('class') }}">
    <polyline fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="2" color="#CC0000" stroke-width="2" points="{{ $points }}" />
</svg>
