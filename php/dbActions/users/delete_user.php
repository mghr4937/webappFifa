<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: DELETE, PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once(dirname(__FILE__).'/../../config/database.php');
include_once(dirname(__FILE__).'/../../objects/user.php');

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare product object
$user = new User($db);

// get id of product to be deleted
$data = json_decode(file_get_contents("php://input"));
// set ID property of product to be deleted
$user->id = $data->id;

//delete 
if($user->delete()){
    http_response_code(200);   
}else{
    http_response_code(400);    
}
?>
