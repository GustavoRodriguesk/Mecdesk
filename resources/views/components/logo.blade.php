@props([
    'variant' => 'color', // 'color' ou 'white'
    'class' => 'h-8 w-auto',
    'alt' => 'MecDesk — Gestão de Oficina'
])

@php
    $src = $variant === 'white' 
        ? asset('images/logo-white.svg') 
        : asset('images/logo.svg');
@endphp

<img src="{{ $src }}" alt="{{ $alt }}" {{ $attributes->merge(['class' => $class]) }}>
