<?php
namespace App\http\controller;

use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

use App\class\model\userModel;


class userController{
    private $userHelper;
    private $modelObj;

    public function __construct($modelObj)
    {
        // echo "from constructor";
        // $this->Umodel = new userModel();
        $this->modelObj = $modelObj;
    }

    //list of all functions regarding user will be here

    public function users()
    {
        $uList = $this->modelObj->usersList();
        return $uList;
    }

    public function login($mobile_no, $password)
    {
        $this->modelObj->loginModel($mobile_no, $password);
    }
}
?>