<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-black hover:bg-[#FF5500] text-white border border-transparent rounded-full font-extrabold text-xs uppercase tracking-wider focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition-all duration-200 shadow-md cursor-pointer']) }}>
    {{ $slot }}
</button>
