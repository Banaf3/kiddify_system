<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#5A9CB5] border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#4A8CA5] active:bg-[#4A8CA5] focus:outline-none focus:ring-2 focus:ring-[#5A9CB5] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>