<?php
require __DIR__ . '/vendor/autoload.php';

use Tqdev\PhpCrudApi\Config\Config;
use Tqdev\PhpCrudApi\Api;
use Nyholm\Psr7Server\ServerRequestCreator;
use Nyholm\Psr7\Factory\Psr17Factory;
use Dotenv\Dotenv;

// -------------------------
// Load environment variables
// -------------------------
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


// Конфигурација за MySQL
$config = new Config([
    'driver'   => $_ENV['DB_DRIVER'],
    'address'  => $_ENV['DB_HOST'],
    'port'     => $_ENV['DB_PORT'],
    'username' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
    'database' => $_ENV['DB_DATABASE'],
]);
$headers = getallheaders();  // <-- this gets headers from client
$clientKey = $headers['X-API-KEY'] ?? ''; // get API key from header

if ($clientKey !== $_ENV['API_KEY']) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized: invalid API key']);
    exit;
}

// Креирај PSR-7 ServerRequest од глобалните променливи
$psr17Factory = new Psr17Factory();
$creator = new ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
);
$request = $creator->fromGlobals();

// Стартувај API
$api = new Api($config);
$response = $api->handle($request);

// Испрати HTTP одговор назад кон клиентот
http_response_code($response->getStatusCode());
foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}
echo $response->getBody();