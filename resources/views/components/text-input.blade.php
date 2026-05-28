@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-cyber-purple/30 bg-cyber-dark/40 text-gray-300 focus:border-cyber-cyan focus:ring-cyber-cyan/50 rounded-md shadow-sm placeholder-gray-500']) }}>
