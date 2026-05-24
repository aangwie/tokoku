<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class SystemUpdateController extends Controller
{
    /**
     * Display the system update page
     */
    public function index()
    {
        $githubToken = Setting::get('github_token', '');
        $githubRepo = Setting::get('github_repo', '');
        
        return view('admin.system.update', compact('githubToken', 'githubRepo'));
    }

    /**
     * Save GitHub settings
     */
    public function saveSettings(Request $request)
    {
        $request->validate([
            'github_token' => 'nullable|string',
            'github_repo' => 'nullable|string',
        ]);

        Setting::set('github_token', $request->github_token ?? '');
        Setting::set('github_repo', $request->github_repo ?? '');

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan GitHub berhasil disimpan!'
        ]);
    }

    /**
     * Pull updates from GitHub
     */
    public function pullUpdate(Request $request)
    {
        try {
            $output = [];
            $githubToken = Setting::get('github_token', '');
            $githubRepo = Setting::get('github_repo', '');

            // Check if git is available
            exec('git --version 2>&1', $gitCheck, $gitReturn);
            if ($gitReturn !== 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Git tidak terinstall di server!',
                    'output' => implode("\n", $gitCheck)
                ]);
            }

            $output[] = "=== Git Version ===";
            $output[] = implode("\n", $gitCheck);
            $output[] = "";

            // Check current branch
            exec('git branch --show-current 2>&1', $branchOutput, $branchReturn);
            $currentBranch = $branchReturn === 0 ? trim($branchOutput[0]) : 'unknown';
            $output[] = "=== Current Branch ===";
            $output[] = "Branch: " . $currentBranch;
            $output[] = "";

            // Check git status before pull
            $hasUncommittedChanges = false;
            exec('git status --porcelain 2>&1', $statusOutput);
            if (!empty($statusOutput)) {
                $hasUncommittedChanges = true;
                $output[] = "=== Warning: Uncommitted Changes Detected ===";
                $output[] = implode("\n", $statusOutput);
                $output[] = "";
                $output[] = "=== Auto-Stashing Changes ===";
                
                // Stash uncommitted changes (including untracked files)
                exec('git stash push -u -m "Auto-stash before pull update" 2>&1', $stashOutput, $stashReturn);
                $output[] = implode("\n", $stashOutput);
                $output[] = "";
                
                if ($stashReturn !== 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal melakukan git stash!',
                        'output' => implode("\n", $output)
                    ]);
                }
            }

            // Fetch updates
            $output[] = "=== Fetching Updates ===";
            if ($githubToken && $githubRepo) {
                // Use token for private repos
                $remoteUrl = "https://{$githubToken}@github.com/{$githubRepo}.git";
                exec("git remote set-url origin {$remoteUrl} 2>&1", $remoteOutput);
                $output[] = "Using authenticated GitHub access...";
            }
            
            exec('git fetch origin 2>&1', $fetchOutput, $fetchReturn);
            $output[] = implode("\n", $fetchOutput);
            $output[] = "";

            // Check for updates
            exec("git rev-list HEAD...origin/{$currentBranch} --count 2>&1", $countOutput);
            $updateCount = isset($countOutput[0]) ? intval($countOutput[0]) : 0;
            
            if ($updateCount === 0) {
                $output[] = "=== No Updates Available ===";
                $output[] = "Aplikasi sudah menggunakan versi terbaru!";
                
                return response()->json([
                    'success' => true,
                    'message' => 'Tidak ada update tersedia',
                    'output' => implode("\n", $output)
                ]);
            }

            $output[] = "=== Updates Available: {$updateCount} commits ===";
            $output[] = "";

            // Show what will be updated
            exec("git log HEAD..origin/{$currentBranch} --oneline --decorate 2>&1", $logOutput);
            $output[] = "=== Commit History ===";
            $output[] = implode("\n", $logOutput);
            $output[] = "";

            // Pull updates
            $output[] = "=== Pulling Updates ===";
            exec('git pull origin ' . $currentBranch . ' 2>&1', $pullOutput, $pullReturn);
            $output[] = implode("\n", $pullOutput);
            $output[] = "";

            if ($pullReturn !== 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal melakukan git pull!',
                    'output' => implode("\n", $output)
                ]);
            }

            $output[] = "=== Update Berhasil! ===";
            $output[] = "Aplikasi berhasil diupdate ke versi terbaru.";
            
            // Inform about stashed changes
            if ($hasUncommittedChanges) {
                $output[] = "";
                $output[] = "=== Perubahan Lokal Tersimpan ===";
                $output[] = "Perubahan lokal Anda telah disimpan di git stash.";
                $output[] = "";
                $output[] = "Untuk melihat stash: git stash list";
                $output[] = "Untuk restore stash: git stash pop";
                $output[] = "Untuk hapus stash: git stash drop";
                $output[] = "";
                $output[] = "⚠️ CATATAN: File error_log dan build.zip sebaiknya ditambahkan ke .gitignore";
            }

            return response()->json([
                'success' => true,
                'message' => 'Update berhasil! ' . $updateCount . ' commit diterapkan.' . ($hasUncommittedChanges ? ' (Perubahan lokal di-stash)' : ''),
                'output' => implode("\n", $output)
            ]);

        } catch (\Exception $e) {
            Log::error('System Update Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'output' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Clear application cache
     */
    public function clearCache(Request $request)
    {
        try {
            $output = [];
            
            $output[] = "=== Clearing Application Cache ===";
            Artisan::call('cache:clear');
            $output[] = Artisan::output();
            
            $output[] = "=== Clearing Config Cache ===";
            Artisan::call('config:clear');
            $output[] = Artisan::output();
            
            $output[] = "=== Clearing Route Cache ===";
            Artisan::call('route:clear');
            $output[] = Artisan::output();
            
            $output[] = "=== Clearing View Cache ===";
            Artisan::call('view:clear');
            $output[] = Artisan::output();
            
            $output[] = "=== Cache Cleared Successfully! ===";

            return response()->json([
                'success' => true,
                'message' => 'Cache berhasil dibersihkan!',
                'output' => implode("\n", $output)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'output' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Run database migrations
     */
    public function runMigrations(Request $request)
    {
        try {
            $output = [];
            
            $output[] = "=== Running Database Migrations ===";
            Artisan::call('migrate', ['--force' => true]);
            $output[] = Artisan::output();
            
            $output[] = "=== Migrations Completed! ===";

            return response()->json([
                'success' => true,
                'message' => 'Migrasi database berhasil dijalankan!',
                'output' => implode("\n", $output)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'output' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Optimize application
     */
    public function optimize(Request $request)
    {
        try {
            $output = [];
            
            $output[] = "=== Optimizing Application ===";
            Artisan::call('optimize');
            $output[] = Artisan::output();
            
            $output[] = "=== Caching Config ===";
            Artisan::call('config:cache');
            $output[] = Artisan::output();
            
            $output[] = "=== Caching Routes ===";
            Artisan::call('route:cache');
            $output[] = Artisan::output();
            
            $output[] = "=== Caching Views ===";
            Artisan::call('view:cache');
            $output[] = Artisan::output();
            
            $output[] = "=== Optimization Completed! ===";

            return response()->json([
                'success' => true,
                'message' => 'Aplikasi berhasil dioptimasi!',
                'output' => implode("\n", $output)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'output' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Install/Update Composer dependencies
     */
    public function composerUpdate(Request $request)
    {
        try {
            $output = [];
            
            // Check if composer is available
            exec('composer --version 2>&1', $composerCheck, $composerReturn);
            if ($composerReturn !== 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Composer tidak terinstall di server!',
                    'output' => implode("\n", $composerCheck)
                ]);
            }

            $output[] = "=== Composer Version ===";
            $output[] = implode("\n", $composerCheck);
            $output[] = "";
            
            $output[] = "=== Installing/Updating Dependencies ===";
            $output[] = "This may take a few minutes...";
            $output[] = "";
            
            exec('composer install --no-dev --optimize-autoloader 2>&1', $installOutput, $installReturn);
            $output[] = implode("\n", $installOutput);
            $output[] = "";
            
            if ($installReturn !== 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Composer install gagal!',
                    'output' => implode("\n", $output)
                ]);
            }
            
            $output[] = "=== Dependencies Updated Successfully! ===";

            return response()->json([
                'success' => true,
                'message' => 'Dependencies berhasil diupdate!',
                'output' => implode("\n", $output)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'output' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Get system information
     */
    public function systemInfo(Request $request)
    {
        try {
            $output = [];
            
            $output[] = "=== System Information ===";
            $output[] = "PHP Version: " . PHP_VERSION;
            $output[] = "Laravel Version: " . app()->version();
            $output[] = "Server: " . $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown';
            $output[] = "OS: " . PHP_OS;
            $output[] = "";
            
            // Git info
            exec('git log -1 --format="%H|%an|%ae|%ad|%s" 2>&1', $gitLog, $gitReturn);
            if ($gitReturn === 0 && !empty($gitLog)) {
                $parts = explode('|', $gitLog[0]);
                $output[] = "=== Current Version ===";
                $output[] = "Commit: " . substr($parts[0], 0, 7);
                $output[] = "Author: " . $parts[1];
                $output[] = "Date: " . $parts[3];
                $output[] = "Message: " . $parts[4];
            }

            return response()->json([
                'success' => true,
                'output' => implode("\n", $output)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'output' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Create storage symbolic link
     */
    public function createStorageLink(Request $request)
    {
        try {
            $output = [];
            
            $output[] = "=== Creating Storage Link ===";
            $output[] = "OS: " . PHP_OS;
            $output[] = "";
            
            // Check if link already exists
            $publicStoragePath = public_path('storage');
            
            if (file_exists($publicStoragePath)) {
                // Check if it's a symbolic link (works on both Windows and Unix)
                $isSymlink = is_link($publicStoragePath);
                
                if ($isSymlink) {
                    $output[] = "Storage link already exists!";
                    $output[] = "Link: " . $publicStoragePath;
                    
                    // Try to get the target (may fail on Windows)
                    try {
                        $target = readlink($publicStoragePath);
                        $output[] = "Target: " . $target;
                    } catch (\Exception $e) {
                        $output[] = "Target: " . storage_path('app/public') . " (expected)";
                    }
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Storage link sudah ada!',
                        'output' => implode("\n", $output)
                    ]);
                } else {
                    $output[] = "Warning: 'public/storage' exists but is not a symbolic link!";
                    $output[] = "Path: " . $publicStoragePath;
                    $output[] = "";
                    $output[] = "Please remove or rename this directory manually first.";
                    $output[] = "Then run this command again.";
                    
                    return response()->json([
                        'success' => false,
                        'message' => 'Direktori public/storage sudah ada tetapi bukan symbolic link!',
                        'output' => implode("\n", $output)
                    ]);
                }
            }
            
            // Create the symbolic link using Artisan command
            $output[] = "Creating symbolic link...";
            $output[] = "";
            
            try {
                Artisan::call('storage:link');
                $artisanOutput = Artisan::output();
                $output[] = $artisanOutput;
            } catch (\Exception $e) {
                $output[] = "Artisan Error: " . $e->getMessage();
                
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat storage link: ' . $e->getMessage(),
                    'output' => implode("\n", $output)
                ]);
            }
            
            $output[] = "";
            $output[] = "=== Storage Link Created Successfully! ===";
            $output[] = "Link: " . $publicStoragePath;
            $output[] = "Target: " . storage_path('app/public');
            $output[] = "";
            $output[] = "✓ Gambar produk sekarang dapat diakses melalui URL /storage/";
            $output[] = "✓ Contoh: /storage/products/image.webp";

            return response()->json([
                'success' => true,
                'message' => 'Storage link berhasil dibuat!',
                'output' => implode("\n", $output)
            ]);

        } catch (\Exception $e) {
            Log::error('Storage Link Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'output' => "Error Details:\n" . $e->getMessage() . "\n\nFile: " . $e->getFile() . "\nLine: " . $e->getLine()
            ]);
        }
    }
}
