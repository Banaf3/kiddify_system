<a {{ $attributes->merge(['class' => 'block w-full px-4 py-3 text-start text-base font-semibold leading-5 transition-all duration-300 ease-in-out rounded-xl']) }}
    style="color: #374151; background: transparent;">
    <span style="color: #374151;">{{ $slot }}</span>
</a>

<style>
    a[class*="dropdown"] {
        color: #374151 !important;
        position: relative;
    }

    a[class*="dropdown"]:hover {
        background: linear-gradient(135deg, rgba(236, 72, 153, 0.15) 0%, rgba(168, 85, 247, 0.15) 100%) !important;
        color: #EC4899 !important;
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(236, 72, 153, 0.15);
    }

    a[class*="dropdown"] span {
        color: inherit !important;
    }
</style>
