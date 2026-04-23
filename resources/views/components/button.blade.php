<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#4169E1] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-800 focus:bg-blue-800 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-[#4169E1] focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150 shadow-lg shadow-blue-500/20']) }}>
    {{ $slot }}
</button>
