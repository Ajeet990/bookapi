<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;



// require ('phpjwt/src/BeforeValidException.php');
// require ('phpjwt/src/JWK.php');
// require ('phpjwt/src/JWT.php');
// require ('phpjwt/src/ExpiredException.php');
// require ('phpjwt/src/SignatureInvalidException.php');

class check_all
{
    public function email($email)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            return true;
        } else {
            return false;
        }
    }

    public function email_exist($email)
    {
        include 'config/db.php';
        $sql = "select * from register where email = '$email'";
        $rst = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($rst);
        if($num > 0){
            return true;
        } else {
            return false;
        }
    }

    public function contact($phone)
    {
             // Allow +, - and . in phone number
     $filtered_phone_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);

     $phone_to_check = str_replace("-", "", $filtered_phone_number);
     if (strlen($phone_to_check) != 10) {
        return false;
     } else {
       return true;
     }
    }

    public function contact_exist($phone)
    {
        include 'config/db.php';
        $sql = "select * from register where mobile_no = '$phone'";
        $rst = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($rst);
        if($num > 0){
            return true;
        } else {
            return false;
        }

    }

    public function validateToken()
    {
    $headers = getallheaders();

        try{
            
            $token = trim($headers['Authorization']);
            $key = "phpApi";
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            if($decoded){
                return "1";
            } 
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}

?>