<?php

class User
{
    // database connection and table name
    private $conn;
    private $table_name = 'users';
    private $TAG = 'Object.User';
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
    try{   
        // query to insert record
        $query = 'INSERT INTO '.$this->table_name.'(name) VALUES (:name);';
        // prepare query
        $this->conn->beginTransaction();
        $stmt = $this->conn->prepare($query);
        // posted values
        $this->name = htmlspecialchars(strip_tags($this->name));
        // bind values
        $stmt->bindParam(':name', $this->name);
        // execute query
        if ($stmt->execute()) {
            $this->conn->commit();
            log_Info($this->TAG,'Usuario Creado || '.$this->name);
            return true;
        }
    }catch(Exception $e){
        log_Error($this->TAG, $e);	
		$this->conn->rollback();
    }
}

// update the user
function update(){
    try{   
        // update query
        $query = "UPDATE " . $this->table_name . " SET name = :name WHERE id = :id;";
        // prepare query statement
        $this->conn->beginTransaction();
        $stmt = $this->conn->prepare($query);
        // posted values
        $this->name=htmlspecialchars(strip_tags($this->name));
        // bind new values
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':id', $this->id);
        // execute the query
        if($stmt->execute()){
            $this->conn->commit();
            log_info($this->TAG,'Usuario Actualizado || '.$this->name);
            return true;
        }
    }catch(Exception $e){
        log_Error($this->TAG, $e);		
		$this->conn->rollback();
    }
}

// delete the product
function delete(){
    try{    
        // delete query   , set as inactive user 
        $query = "UPDATE " . $this->table_name . " SET active = !active WHERE id = :id";    
        // prepare query
        $this->conn->beginTransaction();     
        $stmt = $this->conn->prepare($query);
        // bind id of record to delete
        $stmt->bindParam(':id', $this->id);    
        // execute query
        if($stmt->execute()){
            $this->conn->commit();
            log_info($this->TAG,'Baja Actualizado || '.$this->name);
            return true;
        }
    }catch(Exception $e) {			
        log_Error($this->TAG, $e);		
        $this->conn->rollback();
    }
}

// read all users
function readAll(){
    try{
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        // prepare query statement       
        $stmt = $this->conn->prepare( $query );
        // execute query
        $stmt->execute();
        return $stmt;
    }catch(Exception $e) {			
        log_Error($this->TAG, $e);
    }
}

// read user activos
function readActiveUsers(){
    try{
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " WHERE active = 1 ORDER BY id DESC";
        // prepare query statement
        $this->conn->beginTransaction();
        $stmt = $this->conn->prepare( $query );
        // execute query
        $stmt->execute();
        return $stmt;
    }catch(Exception $e) {			
        log_Error($this->TAG, $e);		
        $this->conn->rollback();
    }
}



}
