<?php

use App\Services\NumeracyAiAgentService;
use Illuminate\Contracts\Console\Kernel;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$service = app(NumeracyAiAgentService::class);
echo "Testing generateOnDemandPackage for 1 question...\n";
$start = microtime(true);
try {
    $res = $service->generateOnDemandPackage('campuran', 'Mudah', 1);
    $dur = round(microtime(true) - $start, 2);
    echo "Took: {$dur}s\n";
    echo 'Questions count: '.count($res['questions'])."\n";
    echo 'AI generated count: '.($res['ai_generated_count'] ?? 0)."\n";
    echo 'AI model: '.($res['ai_model'] ?? 'none')."\n";
    if (! empty($res['questions'][0])) {
        echo 'First Q: '.($res['questions'][0]['title'] ?? '')."\n";
    }
} catch (Throwable $e) {
    echo 'ERROR: '.$e->getMessage()."\n";
}
