@props(['variant' => 'color'])

<x-logo :variant="$variant" {{ $attributes->merge(['class' => 'h-9 w-auto']) }} />

