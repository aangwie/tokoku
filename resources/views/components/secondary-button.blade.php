<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-cyber-dark/40 border border-cyber-purple/30 rounded-md font-semibold text-xs text-gray-300 uppercase tracking-widest shadow-sm hover:bg-cyber-purple/10 hover:border-cyber-cyan/50 hover:text-white focus:outline-none focus:ring-2 focus:ring-cyber-cyan/50 focus:ring-offset-2 focus:ring-offset-cyber-dark disabled:opacity-25 transition-all duration-200']) }}>
    {{ $slot }}
</button>
