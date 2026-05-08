<?php
use Illuminate\Support\Facades\Artisan;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

try {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    
    Artisan::call('migrate', [
        '--force' => true
    ]);

    echo "Installation forcée réussie ! Les tables sont créées.";
} catch (\Exception $e) {
    echo "Erreur durant l'installation : " . $e->getMessage();
}

$kernel->terminate($request, $response);
?>