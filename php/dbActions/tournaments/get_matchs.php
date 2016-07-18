<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
header("Content-Type: application/json; charset=UTF-8");


include_once(dirname(__FILE__).'/../../config/database.php');
include_once(dirname(__FILE__).'/../../objects/tournament.php');


// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$tournament = new Tournament($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set user property values
$tournament->id = $data->id;

// query products
$stmt = $tournament->readAllMatchs();
$num = $stmt->rowCount();

// check if more than 0 record found
if($num > 0){
    $data="";
    $x=1;
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
        $data .= '{';
            $data .= '"id_tournament":"'. $id_tournament . '",';
            $data .= '"user_a":"'  . $user_a . '",';
            $data .= '"user_b":"' . $user_b . '",';
            $data .= '"gol_a":"' . $gol_a . '",';
            $data .= '"gol_b":"' . $gol_b . '"';
        $data .= '}';
        $data .= $x < $num ? ',' : ''; $x++;

      }
}

// json format output
echo '{"records":[' . $data . ']}';
?>
