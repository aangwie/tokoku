@props(['disabled' => false])

<div class="relative">
    <input 
        @disabled($disabled) 
        {{ $attributes->merge(['class' => 'border-cyber-purple/30 bg-cyber-dark/40 text-gray-300 focus:border-cyber-cyan focus:ring-cyber-cyan/50 rounded-md shadow-sm pr-10 placeholder-gray-500']) }}
        type="password"
    >
    
    <button 
        type="button" 
        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-cyber-cyan transition-all duration-200"
        onclick="togglePasswordVisibility(this)"
    >
        <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
    </button>
</div>

<script>
function togglePasswordVisibility(button) {
    const input = button.previousElementSibling;
    const eyeIcon = button.querySelector('#eye-icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        // Show eye with slash (hidden)
        eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l18 18" />';
    } else {
        input.type = 'password';
        // Show normal eye (visible)
        eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
    }
}
</script>