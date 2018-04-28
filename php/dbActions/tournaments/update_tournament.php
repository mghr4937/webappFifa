<?php
// get database connection
include_once(dirname(__FILE__).'/../../config/database.php');
$database = new Database();
$db = $database->getConnection();

// instantiate product object
include_once(dirname(__FILE__).'/../../objects/tournament.php');
$tournament = new Tournament($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set user property values
$user->id = $data->id;
$tournament->name = $data->name;
$tournament->place = $data->place;
$tournament->monthyear = $data->monthyear;

// create the product
if($tournament->update()){
    http_response_code(200);   
}else{
    http_response_code(400);    
}
?>
