@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-[#5A9CB5] focus:ring-[#5A9CB5] rounded-md shadow-sm']) }}>