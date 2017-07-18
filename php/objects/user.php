<?php

class User
{
    // database connection and table name
    private $conn;
    private $table_name = 'users';
    // object properties
    public $id;
    public $name;
    public $active;
    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

// create product
public function create()
{
    // query to insert record
    $query = 'INSERT INTO '.$this->table_name.'(name) VALUES (:name);';
    // prepare query
    $stmt = $this->conn->prepare($query);
    // posted values
    $this->name = htmlspecialchars(strip_tags($this->name));
    // bind values
    $stmt->bindParam(':name', $this->name);
    // execute query
    if ($stmt->execute()) {
        return true;
    } else {
        echo '<pre>';
        print_r($stmt->errorInfo());
        echo '</pre>';
        return false;
    }
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
    //$query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
    $query = "UPDATE " . $this->table_name . " SET active = !active WHERE id = :id";
    // prepare query
    $stmt = $this->conn->prepare($query);
    // bind id of record to delete
    $stmt->bindParam(':id', $this->id);    
    // execute query
    if($stmt->execute()){
        return true;
    }else{
        return false;
    }
}

// read all users
function readAll(){
    // select all query
    $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
    // execute query
    $stmt->execute();
    return $stmt;
}

// read user activos
function readActiveUsers(){
    // select all query
    $query = "SELECT * FROM " . $this->table_name . " WHERE active = 1 ORDER BY id DESC";
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
    // execute query
    $stmt->execute();
    return $stmt;
}



}
