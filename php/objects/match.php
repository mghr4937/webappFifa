<?php

class Match
{
    // database connection and table name
    private $conn;
    private $table_name = 'matchs';
    // object properties
    public $id_tournament;
    public $user_a;
    public $user_b;
    public $gol_a;
    public $gol_b;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

// create product
public function create()
{
    // // query to insert record
    // $query = 'INSERT INTO '.$this->table_name.'(name) VALUES (:name);';
    // // prepare query
    // $stmt = $this->conn->prepare($query);
    // // posted values
    // $this->name = htmlspecialchars(strip_tags($this->name));
    // // bind values
    // $stmt->bindParam(':name', $this->name);
    // // execute query
    // if ($stmt->execute()) {
    //     return true;
    // } else {
    //     echo '<pre>';
    //     print_r($stmt->errorInfo());
    //     echo '</pre>';
    //     return false;
    // }
}

// update the user
function update(){
    // update query
    $query = "UPDATE " . $this->table_name . " SET gol_a = :gol_a, gol_b = :gol_b WHERE id_tournament = :id_t AND id_user_a= (SELECT id from users WHERE name = :user_a) AND id_user_b = (SELECT id from users WHERE name = :user_b);";
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // posted values
    $this->id_tournament=htmlspecialchars(strip_tags($this->id_tournament));
    $this->user_a=htmlspecialchars(strip_tags($this->user_a));
    $this->user_b=htmlspecialchars(strip_tags($this->user_b));
    $this->gol_a=htmlspecialchars(strip_tags($this->gol_a));
    $this->gol_b=htmlspecialchars(strip_tags($this->gol_b));

    // bind new values
    $stmt->bindParam(':id_t', $this->id_tournament);
    $stmt->bindParam(':user_a', $this->user_a);
    $stmt->bindParam(':user_b', $this->user_b);
    $stmt->bindParam(':gol_a', $this->gol_a);
    $stmt->bindParam(':gol_b', $this->gol_b);
    // execute the query
    if($stmt->execute()){
        return true;
    }else{
        return false;
    }
}

// delete the product
function delete(){
    // // delete query
    // $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
    // // prepare query
    // $stmt = $this->conn->prepare($query);
    // // bind id of record to delete
    // $stmt->bindParam(1, $this->id);
    //
    // // execute query
    // if($stmt->execute()){
    //     return true;
    // }else{
    //     return false;
    // }
}

// read products
function readAll($id_t){
    // select all query
    $query = "SELECT id_tournament, (SELECT name FROM TORNEOS.users WHERE id = id_user_a), (SELECT name FROM TORNEOS.users WHERE id = id_user_B), gol_a, gol_b FROM TORNEOS.matchs WHERE id_tournament =".$id_t;
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
    // execute query
    $stmt->execute();
    return $stmt;
}






}
