<?php
use Slim\App;
use Slim\Container;
use Slim\Http\Environment;
use Slim\Http\Request;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use App\http\controller\userController;
use App\class\model\userModel;

use App\Db;

require __DIR__. '/../vendor/autoload.php';

$connection = new Db();
$connection->getConnection();


$app = AppFactory::create();



$app->get('/', function (Request $request, Response $response, array $args) {
    // $name = $args['name'];
    $response->getBody()->write("Api development");

    return $response;
});

$app->get('/users', function (Request $request, Response $response) {
    $modelObj = new userModel($conn);
    // $userObj = new userController($modelObj);
    
    // $uList = $userObj->users();
    $response->getBody()->write("users endpoint");
    return $response->withHeader('content-type','application/json')->withStatus(200);
});

$app->post('/login', function (Request $request, Response $response) {
    require __DIR__. '/../app/mobileApp/include/config.php';
    $modelObj = new userModel($conn);
    $userObj = new userController($modelObj);

    $params = $request->getParsedBody();
    $mobile_no = trim($params['mobile_no'] ?? '');
    $password = trim($params['password'] ?? '');

    $userObj->login($mobile_no, $password);
});

$app->run();