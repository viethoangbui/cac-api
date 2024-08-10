<?php
use Cacti\Api\Data\Graph;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->get('/graphs', function (Request $request, Response $response, $args) {
    $queryParams = $request->getQueryParams();

    $auth = $queryParams['auth'] ?? null;
    $type = $queryParams['type'] ?? null;
    $fromDate = $queryParams['from_date'] ?? null;
    $toDate = $queryParams['to_Date'] ?? null;

    $result = new stdClass();

    if($auth && $type && $fromDate && $toDate) {
        $amount = rand(1,5);

        $graph = new Graph($amount);
        $graphs = $graph->create();

        foreach ($graphs as $item) {
            $key = generateRandomString();
            $result->$key = $item;
        }
    }

    $payload = json_encode($result);
    $response->getBody()->write($payload);

    return $response
          ->withHeader('Content-Type', 'application/json')
          ->withStatus(200);
});

$app->run();