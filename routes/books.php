<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


// require ('phpjwt/src/BeforeValidException.php');
// require ('phpjwt/src/JWK.php');
// require ('phpjwt/src/JWT.php');
// require ('phpjwt/src/ExpiredException.php');
// require ('phpjwt/src/SignatureInvalidException.php');

$app = AppFactory::create();

$app->post('/addBook', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("adding book");
    return $response->withHeader('Content-type', 'application/json')
    ->withStatus(200);

});