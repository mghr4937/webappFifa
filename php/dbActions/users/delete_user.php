<?php
// include database and object files
include_once(dirname(__FILE__).'/../../config/database.php');
include_once(dirname(__FILE__).'/../../objects/user.php');

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare product object
$user = new User($db);

// get id of product to be edited
$data = json_decode(file_get_contents("php://input"));
// set ID property of product to be edited
$user->id = $data->id;
//$user->name = $data->name;
// update the product
if($user->delete()){
    echo "User was deleted.";
}

// if unable to update the product, tell the user
else{
    echo "Unable to delete User.";
}
?>
