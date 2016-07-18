<?php
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
    echo "User was created.";
}
// if unable to create the product, tell the user
else{
    echo "Unable to user product.";
}
?>
