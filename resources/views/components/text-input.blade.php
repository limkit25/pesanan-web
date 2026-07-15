@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-200 focus:border-pink-500 focus:ring-pink-500 rounded-xl shadow-sm bg-gray-50/50 focus:bg-white transition-colors duration-200']) }}>
