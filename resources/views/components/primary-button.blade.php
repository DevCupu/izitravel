<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-2.5 bg-blue-600 border border-transparent rounded-xl font-bold text-sm text-white hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 active:scale-95 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-150 shadow-lg shadow-blue-500/20']) }}>
    {{ $slot }}
</button>
