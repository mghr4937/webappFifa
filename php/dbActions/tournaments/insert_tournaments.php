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
$tournament->name = $data->name;
$tournament->place = $data->place;
$tournament->monthyear = $data->monthyear;
$tournament->name = $data->name;
$tournament->users = $data->users;
// create the product
if($tournament->create()){
    echo "Tournament was created.";
}
// if unable to create the product, tell the user
else{
    echo "Unable to insert tournament.";
}
?>
