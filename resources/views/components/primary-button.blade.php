<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex justify-center items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-pink-500 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:from-orange-600 hover:to-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full shadow-md']) }}>
    {{ $slot }}
</button>
