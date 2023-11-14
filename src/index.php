<?php
declare(strict_types=1);

require_once '../vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface;
use WilliamCastro\ApiFacebookConversions\ApiNotificationsService;

// Cloudflare proxy helper
if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}

$pixelId = '';
$accessToken = '';

$apiNotificationClient = (new ApiNotificationsService)
    ->setPixelId($pixelId)
    ->setAccessToken($accessToken);

$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

$responseFactory = new Laminas\Diactoros\ResponseFactory();
$strategy        = new League\Route\Strategy\JsonStrategy($responseFactory);
$router          = (new League\Route\Router)->setStrategy($strategy);

// map a route
$router->map('GET', '/', function (ServerRequestInterface $request) use ($apiNotificationClient) {
    try {
        return $apiNotificationClient->setPayload([
                                                      'data'            => [
                                                          [
                                                              "event_name" => "PageView",
                                                              "event_time" => time(),
                                                              "user_data" => [
                                                                  'client_ip_address' => $request->getServerParams()['REMOTE_ADDR'],
                                                                  'client_user_agent' => $request->getServerParams()['HTTP_USER_AGENT'],
                                                                  'em' => hash('sha256', 'ejemplo@example.com'), // Aplicar função de hash ao e-mail
                                                                  'ph' => hash('sha256', '123456789'), // Aplicar função de hash ao telefone
                                                              ],
                                                              'event_source_url' =>  isset($request->getServerParams()['HTTP_REFERER']) ? $request->getServerParams()['HTTP_REFERER'] : '', // Obtém o event_source_url automaticamente
                                                              "opt_out" => false,
                                                              "event_id" => uniqid(),
                                                              "action_source" => "website",
                                                              "data_processing_options" => [],
                                                              "data_processing_options_country" => 0,
                                                              "data_processing_options_state" => 0,
                                                          ]
                                                      ],
                                                      'test_event_code' => 'TEST51508',// Código de teste de eventos
                                                  ])
                                     ->execute();
    } catch (\Exception $e) {
        return [$e->getMessage()];
    }
});

$response = $router->dispatch($request);

(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);