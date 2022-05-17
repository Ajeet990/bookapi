<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


require ('phpjwt/src/BeforeValidException.php');
require ('phpjwt/src/JWK.php');
require ('phpjwt/src/JWT.php');
require ('phpjwt/src/ExpiredException.php');
require ('phpjwt/src/SignatureInvalidException.php');

$app = AppFactory::create();

// get all list of users
$app->get('/users', function (Request $request, Response $response, array $args) {
    include 'config/db.php';
    $check = new check_all();
    $rst = $check->validateToken();
    if($rst == "1"){

        $get_user_qry = "select * from register";
        $get_user_rst = mysqli_query($conn, $get_user_qry);
        $user_array = array();
        while ($row = mysqli_fetch_assoc($get_user_rst)){
            array_push($user_array, $row);
        }
        $response->getBody()->write(json_encode($user_array));
            return $response
                ->withHeader('content-type','application/json')
                ->withStatus(200);
    } 
    else {
        $response->getBody()->write("Token not valid");
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(200);
    }
});

// Reister a new user 
$app->post('/signup', function (Request $request, Response $response, array $args) {
    include 'config/db.php';

    $check = new check_all();

    $params = $request->getParsedBody();
    $image = $_FILES['image'];
    $img_name = $image['name'];
    $img_path = $image['tmp_name'];
    $dest = "img/".$img_name;
    move_uploaded_file($img_path, $dest);
    // $image = trim($params['image'] ?? '');
    $name = trim($params['name'] ?? '');
    $mobile_no = trim($params['mobile_no'] ?? '');
    $address = trim($params['address'] ?? '');
    $email = trim($params['email'] ?? '');
    $password = trim($params['password'] ?? '');

    try{
        $is_valid_email = $check->email($email);
        $is_exist = $check->email_exist($email);

        if($is_valid_email && !$is_exist){
            $is_valid_mobile = $check->contact($mobile_no);
            $is_contact_exist = $check->contact_exist($mobile_no);
                if($is_valid_mobile && !$is_contact_exist){

                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    $qry = "INSERT INTO `register`(`image`, `user_name`, `mobile_no`, `address`, `email`,`password`)  VALUES ('$dest', '$name', '$mobile_no', '$address', '$email', '$hashed_password')";
                    $rst = mysqli_query($conn, $qry);
                    if($rst){
                        $response->getBody()->write(json_encode('Registerd'));
                    return $response
                    ->withHeader('content-type','application/json')
                    ->withStatus(200);

                    }
                }else {
            $response->getBody()->write('invalide Contact number Or contant number already exist.');
            return $response
            ->withHeader('content-type','application/json')
            ->withStatus(200);
        }
        } else {
            $response->getBody()->write('invalid Email address Or email already exists. Please use another email address');
            return $response
            ->withHeader('content-type','application/json')
            ->withStatus(200);

        }

    }catch(PDOException $e) {
            $error = array(
                "message" => $e->getMessage()
            );
            $response->getBody()->write(json_encode($error));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(500);
    
        }

});

// LogIn code
$app->post('/login', function (Request $request, Response $response, array $args) {
    include 'config/db.php';
    $params = $request->getParsedBody();
    $mobile_no = trim($params['mobile_no'] ?? '');
    $password = trim($params['password'] ?? '');

    $qryCheckMobile_no = "select * from register where mobile_no = '$mobile_no'";
    $rst = mysqli_query($conn, $qryCheckMobile_no);
    $loginData = mysqli_fetch_assoc($rst);
    if(mysqli_num_rows($rst) > 0){
        if(password_verify($password, $loginData['password'])){
            $key = "phpApi";
            $payload = array(
                $mobile = $mobile_no,
                $pass = $password
            );
            $jwt = JWT::encode($payload, $key, 'HS256');

            $response->getBody()->write("Login Success\n".$jwt);
            return $response
                ->withHeader('content-type','application/json')
                ->withStatus(200);
        } else {
            $response->getBody()->write("Invalid password");
            return $response
                ->withHeader('content-type','application/json')
                ->withStatus(200);
        }


    } else {
        $response->getBody()->write("Contact number doesn't exits");
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(200);
    }

});

//end point to validate the token
$app->get('/validate', function(Request $req, Response $response) {
    $check = new check_all();

    $rst = $check->validateToken();
    if($rst == "1"){
        $response->getBody()->write("Valid token");
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(200);
    } else {
        $response->getBody()->write("InValid token");
        return $response
            ->withHeader('content-type','application/json')
            ->withStatus(200);
    }

});


//End Point to add book
$app->post('/addBook', function (Request $request, Response $response) {
    include 'config/db.php';

    $check = new check_all();
    $rst = $check->validateToken();

    if($rst == '1') {
        $params = $request->getParsedBody();
        $bookName = trim($params['name'] ?? '');
        $bookGenre = trim($params['genre'] ?? '');
        $bookAuthor = trim($params['author'] ?? '');
        $bookEdition = trim($params['edition'] ?? '');
        $bookOwner = trim($params['owner_id'] ?? '');
        $bookDescription = trim($params['description'] ?? '');
        $image = $_FILES['image'];
        $img_name = $image['name'];
        $img_path = $image['tmp_name'];
        $dest = "img/".$img_name;
        move_uploaded_file($img_path, $dest);

        $qry = "INSERT INTO `books`(`image`, `book_name`, `genre`, `author`, `edition`,`description`, `owner_id`)  VALUES ('$dest', '$bookName', '$bookGenre', '$bookAuthor', '$bookEdition', '$bookDescription', '$bookOwner')";
        $result = mysqli_query($conn, $qry);

        if($result){
            $response->getBody()->write("Book added");
            return $response
            ->withHeader('content-type','application/json')
            ->withStatus(200);
        } else {
            $response->getBody()->write("Book not added");
            return $response
            ->withHeader('content-type','application/json')
            ->withStatus(200);
        }
    }

});
?>