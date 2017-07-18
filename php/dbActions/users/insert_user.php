<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// get database connection
include_once(dirname(__FILE__).'/../../config/database.php');
$database = new Database();
$db = $database->getConnection();

// instantiate product object
include_once(dirname(__FILE__).'/../../objects/user.php');
$user = new User($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set user property values
$user->name = $data->name;

// create the product
if($user->create()){
    http_response_code(200);
}
// if unable to create the product, tell the user
else{
   http_response_code(403);
}
?>
