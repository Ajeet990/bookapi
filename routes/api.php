<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
require __DIR__. '/../app/mobileApp/include/config.php';

$app->post('/login', function (Request $request, Response $response) {
    $modelObj = new userModel($conn);
    $userObj = new userController($modelObj);

    $params = $request->getParsedBody();
    $mobile_no = trim($params['mobile_no'] ?? '');
    $password = trim($params['password'] ?? '');

    $userObj->login($mobile_no, $password);
});

?>