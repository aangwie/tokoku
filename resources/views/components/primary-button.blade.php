<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyber-purple to-cyber-cyan border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:shadow-neon-cyan focus:outline-none focus:ring-2 focus:ring-cyber-cyan/50 focus:ring-offset-2 focus:ring-offset-cyber-dark active:scale-95 transition-all duration-200']) }}>
    {{ $slot }}
</button>
