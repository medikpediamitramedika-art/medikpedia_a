<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Di hosting public_html = root project, path.public tetap base_path()
        // Di lokal, path.public = base_path('public')
        $this->app->bind('path.public', function () {
            if (file_exists(base_path('vendor/autoload.php')) &&
                file_exists(base_path('index.php'))) {
                return base_path(); // hosting: public_html adalah root
            }
            return base_path('public'); // lokal: subfolder public/
        });
    }

    public function boot(): void
    {
        // Vite build directory langsung di root public_html/build
        Vite::useBuildDirectory('build');

        // Pastikan folder storage selalu ada
        $dirs = [
            storage_path('framework/views'),
            storage_path('framework/cache'),
            storage_path('framework/sessions'),
            storage_path('app/public'),
            storage_path('app/public/medicines'),
        ];

        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0775, true);
            }
        }

        // Buat folder storage/medicines di public path (untuk hosting tanpa symlink)
        $publicStorageMedicines = public_path('storage/medicines');
        if (!is_dir($publicStorageMedicines)) {
            @mkdir($publicStorageMedicines, 0775, true);
        }
    }
}
