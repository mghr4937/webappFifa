<?php

class Tournament
{
    // database connection and table name
    private $conn;
    private $table_name = 'tournaments';
    // object properties
    public $id;
    public $name;
    public $monthyear;
    public $place;
    public $users;
    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

// create product
public function create()
{
    // query to insert record
    $query = 'INSERT INTO '.$this->table_name.'(name, monthyear, place) VALUES (:name, :monthyear, :place);';
    // prepare query
    $stmt = $this->conn->prepare($query);
    // posted values
    $this->name = htmlspecialchars(strip_tags($this->name));
    $this->monthyear = htmlspecialchars(strip_tags($this->monthyear));
    $this->place = htmlspecialchars(strip_tags($this->place));
    // bind values
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':monthyear', $this->monthyear);
    $stmt->bindParam(':place', $this->place);

    // execute query
    if ($stmt->execute() ) {
      $query3 = 'SELECT MAX(id) AS MAX FROM TORNEOS.tournaments';
      $stmt3 = $this->conn->prepare($query3);
      $stmt3->execute();
      while($row = $stmt3->fetch(PDO::FETCH_ASSOC)){
        extract($row);
      }
      $bool = false;
      for ($i=0; $i < count($this->users); $i++) {
        $query2 = 'INSERT INTO tournament_user (id_user, id_tournament) VALUES  (:id_user,'.$MAX.');';
        $stmt2 = $this->conn->prepare($query2);
        $stmt2->bindParam(':id_user', $this->users[$i]->id);
        if ($stmt2->execute()) {
            $bool = true;
        }else{
          echo '<pre>';
          print_r($stmt->errorInfo());
          echo '</pre>';
          //return false;
        }
      }
      $bool = $this->fixture($MAX);
      return $bool;
    } else {
        echo '<pre>';
        print_r($stmt->errorInfo());
        echo '</pre>';
        return false;
    }
}


public function fixture($idTournament){
  $players = $this->users;
  $matchs = array();
  $bool = false;
  foreach($players as $k){
        foreach($players as $j){
                if($k == $j){
                        continue;
                }
                $z = array($k,$j);
                sort($z);
                if(!in_array($z,$matchs)){
                        $matchs[] = $z;
                }
        }
      }
      foreach ($matchs as $match) {
          $query = 'INSERT INTO TORNEOS.matchs (id_tournament ,id_user_A ,id_user_B) VALUES(:id_tournament ,:id_user_A ,:id_user_B);';
          $stmt = $this->conn->prepare($query);
          //print_r($match);
          $stmt->bindParam(':id_tournament', $idTournament);
          $stmt->bindParam(':id_user_A', $match[0]->id);
          $stmt->bindParam(':id_user_B', $match[1]->id);
          if($stmt->execute()){
            $bool = true;
          }else{
            echo '<pre>';
            print_r($stmt->errorInfo());
            echo '</pre>';
            return false;
          }
      }
      return true;
}
// update the user
function update(){
    // update query
    $query = "UPDATE " . $this->table_name . " SET name = :name WHERE id = :id;";
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // posted values
    $this->name=htmlspecialchars(strip_tags($this->name));
    // bind new values
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':id', $this->id);
    // execute the query
    if($stmt->execute()){
        return true;
    }else{
        return false;
    }
}

// delete the product
function delete(){
    // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
    // prepare query
    $stmt = $this->conn->prepare($query);
    // bind id of record to delete
    $stmt->bindParam(1, $this->id);

    // execute query
    if($stmt->execute()){
        return true;
    }else{
        return false;
    }
}


function readAll(){
    // select all query
    $query = "SELECT id, name, place, monthyear, (SELECT COUNT(*) FROM TORNEOS.tournament_user where id = id_tournament) as count FROM TORNEOS.tournaments ORDER BY id DESC";
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
    // execute query
    $stmt->execute();
    return $stmt;
}

// read products
function readAllMatchs(){
    // select all query
    $query = "SELECT id_tournament, (SELECT name from TORNEOS.users where id = id_user_a) as user_a, (SELECT name from TORNEOS.users where id = id_user_B) as user_b, gol_a, gol_b from TORNEOS.matchs where id_tournament = ?";
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
    $stmt->bindParam(1, $this->id);
    // execute query
    $stmt->execute();
    return $stmt;
}

function readAllMatchsUser($id_user){
  // select all query
  $query = "SELECT (SELECT name FROM TORNEOS.users WHERE id = id_user_a), (SELECT name FROM TORNEOS.users WHERE id = id_user_B), gol_a, gol_b FROM TORNEOS.matchs WHERE id_tournament =? AND id_user_a =? OR id_user_B =?";
  // prepare query statement
  $stmt = $this->conn->prepare( $query );
    $stmt->bindParam(1, $this->id);
    $stmt->bindParam(2, $this->$id_user);
    $stmt->bindParam(2, $this->$id_user);
  // execute query
  $stmt->execute();
  return $stmt;
}

}
