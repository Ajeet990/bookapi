<?php
namespace App\class\model;


// use Psr\Http\Message\ResponseInterface as Response;
// use Psr\Http\Message\ServerRequestInterface as Request;
// use Slim\Factory\AppFactory;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


require ('phpjwt/src/BeforeValidException.php');
require ('phpjwt/src/JWK.php');
require ('phpjwt/src/JWT.php');
require ('phpjwt/src/ExpiredException.php');
require ('phpjwt/src/SignatureInvalidException.php');

class userModel
{
    private $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function usersList()
    {
        $all = array();
        $allUsers = $this->conn->query("select * from register");
        while($row = mysqli_fetch_assoc($allUsers)){
            array_push($all, $row);
        }
        return $all;


    }

    public function loginModel($mobile_no, $password)
    {
        $qryCheckMobile_no = "select * from register where mobile_no = '$mobile_no'";
        $rst = mysqli_query($this->conn, $qryCheckMobile_no);
        $loginData = mysqli_fetch_assoc($rst);
        if(mysqli_num_rows($rst) > 0){
            if(password_verify($password, $loginData['password'])){
                return true;
            } else {
                return false;
            }
        }else{
            return false;
        }
    
    }
}
?>