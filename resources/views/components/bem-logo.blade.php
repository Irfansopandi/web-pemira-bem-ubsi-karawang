@props(['size' => 'md'])

@php
    $dimensions = match($size) {
        'sm' => 'w-10 h-10',
        'md' => 'w-14 h-14',
        'lg' => 'w-20 h-20',
        'xl' => 'w-24 h-24',
        default => 'w-14 h-14',
    };
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-center justify-center shrink-0 ' . $dimensions]) }}>
    <svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full drop-shadow-sm">
        <!-- Outer Gold Ring -->
        <circle cx="60" cy="60" r="56" fill="#FDFDFD" stroke="#CE9501" stroke-width="3.5"/>
        <circle cx="60" cy="60" r="50" fill="#940c10" stroke="#CE9501" stroke-width="1.5"/>
        
        <!-- Subtle Inner Decorative Ring -->
        <circle cx="60" cy="60" r="46" stroke="#CE9501" stroke-width="0.8" stroke-dasharray="3 2" opacity="0.6"/>

        <!-- Center Shield -->
        <path d="M60 22L78 30V56C78 68 60 78 60 78C60 78 42 68 42 56V30L60 22Z" fill="#FDFDFD" stroke="#CE9501" stroke-width="1.5"/>
        
        <!-- BEM Emblem Flame / Torch / Star -->
        <path d="M60 34L63.5 43H73L65.5 48.5L68 58L60 52.5L52 58L54.5 48.5L47 43H56.5L60 34Z" fill="#CE9501"/>
        <circle cx="60" cy="46" r="3" fill="#940c10"/>

        <!-- Banner Ribbon for BEM UBSI -->
        <path d="M30 84L40 79H80L90 84L85 91H35L30 84Z" fill="#CE9501"/>
        <text x="60" y="87" text-anchor="middle" font-family="'Instrument Sans', sans-serif" font-weight="800" font-size="7.5" fill="#940c10" letter-spacing="0.5">BEM UBSI</text>

        <!-- Subtext Karawang -->
        <text x="60" y="99" text-anchor="middle" font-family="'Instrument Sans', sans-serif" font-weight="700" font-size="6" fill="#CE9501" letter-spacing="1">KARAWANG</text>
    </svg>
</div>

