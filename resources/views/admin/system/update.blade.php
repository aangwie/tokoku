<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">🔄 Update Sistem</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Kelola update aplikasi dan maintenance sistem</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Panel - Actions --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- GitHub Settings --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                        GitHub Settings
                    </h2>
                    <form id="github-settings-form" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">GitHub Token</label>
                            <input type="password" name="github_token" value="{{ $githubToken }}"
                                   placeholder="github_pat_xxxxxxxxxx"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Personal Access Token untuk private repo</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Repository</label>
                            <input type="text" name="github_repo" value="{{ $githubRepo }}"
                                   placeholder="username/repository"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: username/repo-name</p>
                        </div>
                        <button type="submit" class="w-full px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition text-sm">
                            💾 Simpan Pengaturan
                        </button>
                    </form>
                </div>

                {{-- Update Actions --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">⚡ Actions</h2>
                    <div class="space-y-2">
                        <button onclick="executeAction('system-info')" class="action-btn w-full px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition text-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            System Info
                        </button>
                        <button onclick="executeAction('pull-update')" class="action-btn w-full px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition text-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Pull Update dari GitHub
                        </button>
                        <button onclick="executeAction('composer-update')" class="action-btn w-full px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition text-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            Update Dependencies
                        </button>
                        <button onclick="executeAction('run-migrations')" class="action-btn w-full px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition text-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/></svg>
                            Run Migrations
                        </button>
                        <button onclick="executeAction('clear-cache')" class="action-btn w-full px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg transition text-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Clear Cache
                        </button>
                        <button onclick="executeAction('optimize')" class="action-btn w-full px-4 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white font-medium rounded-lg transition text-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Optimize Application
                        </button>
                    </div>
                </div>
            </div>

            {{-- Right Panel - Terminal Output --}}
            <div class="lg:col-span-2">
                <div class="bg-gray-900 rounded-xl shadow-lg border border-gray-700 overflow-hidden">
                    <div class="bg-gray-800 px-4 py-3 flex items-center justify-between border-b border-gray-700">
                        <div class="flex items-center gap-2">
                            <div class="flex gap-1.5">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-300 ml-2">Terminal Output</span>
                        </div>
                        <button onclick="clearTerminal()" class="text-xs text-gray-400 hover:text-white transition">
                            Clear
                        </button>
                    </div>
                    <div id="terminal-output" class="p-4 font-mono text-sm text-green-400 h-[600px] overflow-y-auto">
                        <div class="text-gray-500">$ Waiting for command...</div>
                        <div class="text-gray-500 mt-1">Click any action button to execute</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Terminal output element
        const terminal = document.getElementById('terminal-output');

        // Add line to terminal
        function addToTerminal(text, type = 'info') {
            const colors = {
                'info': 'text-green-400',
                'success': 'text-cyan-400',
                'error': 'text-red-400',
                'warning': 'text-yellow-400',
                'command': 'text-blue-400'
            };
            
            const line = document.createElement('div');
            line.className = colors[type] || 'text-gray-300';
            line.textContent = text;
            terminal.appendChild(line);
            terminal.scrollTop = terminal.scrollHeight;
        }

        // Clear terminal
        function clearTerminal() {
            terminal.innerHTML = '<div class="text-gray-500">$ Terminal cleared</div>';
        }

        // Disable all action buttons
        function disableButtons(disabled) {
            document.querySelectorAll('.action-btn').forEach(btn => {
                btn.disabled = disabled;
                if (disabled) {
                    btn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    btn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            });
        }

        // Execute action
        async function executeAction(action) {
            const actionMap = {
                'system-info': { url: '/admin/system/info', label: 'System Info' },
                'pull-update': { url: '/admin/system/pull-update', label: 'Pull Update' },
                'composer-update': { url: '/admin/system/composer-update', label: 'Composer Update' },
                'run-migrations': { url: '/admin/system/run-migrations', label: 'Run Migrations' },
                'clear-cache': { url: '/admin/system/clear-cache', label: 'Clear Cache' },
                'optimize': { url: '/admin/system/optimize', label: 'Optimize' }
            };

            const actionData = actionMap[action];
            if (!actionData) return;

            // Confirm for destructive actions
            if (['pull-update', 'run-migrations', 'composer-update'].includes(action)) {
                if (!confirm(`Apakah Anda yakin ingin menjalankan ${actionData.label}?`)) {
                    return;
                }
            }

            disableButtons(true);
            addToTerminal(`\n$ Executing: ${actionData.label}...`, 'command');
            addToTerminal('Please wait...', 'warning');

            try {
                const response = await fetch(actionData.url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const result = await response.json();

                if (result.success) {
                    addToTerminal('\n' + result.output, 'info');
                    if (result.message) {
                        addToTerminal('\n✓ ' + result.message, 'success');
                    }
                } else {
                    addToTerminal('\n' + (result.output || ''), 'error');
                    addToTerminal('\n✗ ' + (result.message || 'Operation failed'), 'error');
                }
            } catch (error) {
                addToTerminal('\n✗ Error: ' + error.message, 'error');
            } finally {
                disableButtons(false);
                addToTerminal('\n$ Ready for next command\n', 'command');
            }
        }

        // GitHub settings form
        document.getElementById('github-settings-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            try {
                const response = await fetch('/admin/system/save-settings', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    addToTerminal('\n$ ' + result.message, 'success');
                    if (window.Swal) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: result.message,
                            icon: 'success',
                            confirmButtonColor: '#4f46e5'
                        });
                    }
                } else {
                    addToTerminal('\n✗ ' + (result.message || 'Failed to save settings'), 'error');
                }
            } catch (error) {
                addToTerminal('\n✗ Error: ' + error.message, 'error');
            }
        });

        // Load system info on page load
        window.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => executeAction('system-info'), 500);
        });
    </script>
</x-app-layout>