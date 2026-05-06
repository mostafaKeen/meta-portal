<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Modules\WhatsAppQR\Services\WhatsAppService;

$service = new WhatsAppService();
$sessionId = 'test1234';
$to = '201129274930';
$message = 'Hello from MetaPortal! Your WhatsApp QR integration is now fully functional. 🎉';

echo "Sending message to $to via session $sessionId...\n";

$result = $service->sendMessage($sessionId, $to, $message);

print_r($result);
