<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
header("Content-Type: application/json; charset=UTF-8");
// get database connection
include_once(dirname(__FILE__).'/../../config/database.php');
$database = new Database();
$db = $database->getConnection();

// instantiate product object
include_once(dirname(__FILE__).'/../../objects/match.php');
$match = new Match($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set user property values
$match->id_tournament = $data->id_tournament;
$match->user_a = $data->user_a;
$match->user_b = $data->user_b;
$match->gol_a = $data->gol_a;
$match->gol_b = $data->gol_b;
// create the product
if($match->update()){
    echo json_encode("Match was Updated.");
}
// if unable to create the product, tell the user
else{
    echo json_encode("Unable to update match.");
}
?>
