<?php

class Tournament{

	// database connection and table name
	private $conn;
	private $table_name = 'tournaments';
    private $TAG = 'Object.Tournament';
    
	// object properties
	public $id;
	public $name;
	public $monthyear;
	public $place;
	public $users;
	public $has_final;
	public $is_finish;

	// constructor with $db as database connection
	public function __construct($db)	{
		$this->conn = $db;
	}
	// create product
	public function create(){
		try {
			// query to insert record
			$query = 'INSERT INTO ' . $this->table_name . '(name, monthyear, place) VALUES (:name, :monthyear, :place);';
			// prepare query
			$this->conn->beginTransaction();
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
			if ($stmt->execute()) {
				$query3 = 'SELECT MAX(id) AS MAX FROM '.$this->table_name .'';
				$stmt3 = $this->conn->prepare($query3);
				if ($stmt3->execute()) {
					$row = $stmt3->fetch(PDO::FETCH_ASSOC);
					extract($row);
				}				
				$bool = false;
				for ($i = 0; $i < count($this->users); $i++) {
					$query2 = 'INSERT INTO tournament_user (id_user, id_tournament) VALUES  (:id_user,:max);';
					$stmt2 = $this->conn->prepare($query2);
					$stmt2->bindParam(':id_user', $this->users[$i]->id);
					$stmt2->bindParam(':max', $MAX);
					if ($stmt2->execute()) {
						$bool = true;
					}
				}
				$bool = $this->fixture($MAX);
				$this->conn->commit();
				log_Info($this->TAG, 'Torneo Creado || '.$this->name.' - '.$this->place);
				return $bool;
			}			
		}
		catch(Exception $e) {			
            log_error($this->TAG, $e);	
			$this->conn->rollback();
		}
	}

	public function fixture($idTournament){
		try{		
			$players = $this->users;
			$matchs = array();
			$bool = false;
			foreach($players as $k) {
				foreach($players as $j) {
					if ($k == $j) {
						continue;
					}
					$z = array($k,$j);
					sort($z);
					if (!in_array($z, $matchs)){
						$matchs[] = $z;
					}
				}
			}
			foreach($matchs as $match){
				$query = 'INSERT INTO matchs (id_tournament ,id_user_A ,id_user_B) VALUES(:id_tournament ,:id_user_A ,:id_user_B);';
				$stmt = $this->conn->prepare($query);
				// print_r($match);
				$stmt->bindParam(':id_tournament', $idTournament);
				$stmt->bindParam(':id_user_A', $match[0]->id);
				$stmt->bindParam(':id_user_B', $match[1]->id);
				if ($stmt->execute()) {
					$bool = true;
				}				
			}
			log_Info($this->TAG, 'Fixture Creado || '.$this->name.' - '.$this->place);			
			return true;
		}catch(Exception $e) {			
            log_error($this->TAG, $e);	
			$this->conn->rollback();
		}
	}
	// update the tournament
	function update(){
		try{
			// update query
			$query = "UPDATE " . $this->table_name . " SET name = :name, place = :place, monthyear = :monthyear WHERE id = :id;";
			// prepare query statement
			$this->conn->beginTransaction();
			$stmt = $this->conn->prepare($query);
			// posted values
			$this->name = htmlspecialchars(strip_tags($this->name));
			$this->place = htmlspecialchars(strip_tags($this->place));
			$this->monthyear = htmlspecialchars(strip_tags($this->monthyear));
			// bind new values
			$stmt->bindParam(':name', $this->name);
			$stmt->bindParam(':place', $this->place);
			$stmt->bindParam(':monthyear', $this->monthyear);
			$stmt->bindParam(':id', $this->id);
			// execute the query
			if ($stmt->execute()) {
				$this->conn->commit();
				log_Info($this->TAG, 'Torneo Actualizado || '.$this->name.' - '.$this->place);	
				return true;
			}
		}catch(Exception $e) {			
            log_error($this->TAG, $e);	
			$this->conn->rollback();
		}
    }
    
	function setFinal(){
		$query = "UPDATE " . $this->table_name . " SET has_final = 1 , is_finish = 1 WHERE id = :id;";
		// prepare query statement
		$stmt = $this->conn->prepare($query);
		// bind new values
		$stmt->bindParam(':id', $this->id);
		// execute the query
		if ($stmt->execute()) {
			return true;
		}
		else {
			return false;
		}
    }
    
	// delete the product
	function delete(){
		try{	
		// delete query
			$query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
			// prepare query
			$this->conn->beginTransaction();
			$stmt = $this->conn->prepare($query);
			// bind id of record to delete
			$stmt->bindParam(1, $this->id);
			// execute query
			if ($stmt->execute()) {
				$this->conn->commit();
				return true;
			}			
		}catch(Exception $e) {			
            log_error($this->TAG, $e);	
			$this->conn->rollback();
		}
    }
    
	function readAll(){
		try{
		// select all query
			$query = "SELECT id, name, place, monthyear, has_final, is_finish,  (SELECT COUNT(*) FROM tournament_user where id = id_tournament) AS count FROM tournaments ORDER BY id DESC";
			// prepare query statement
			$stmt = $this->conn->prepare($query);
			// execute query
			$stmt->execute();
			return $stmt;
		}catch(Exception $e) {			
            log_error($this->TAG, $e);	
			$this->conn->rollback();
		}
    }
    
	// read products
	function readAllMatchs(){
		// select all query
		$query = "SELECT id_tournament, (SELECT name from users where id = id_user_a) AS user_a, (SELECT name FROM users WHERE id = id_user_B) AS user_b, gol_a, gol_b FROM matchs WHERE is_final = 0 AND id_tournament = ?";
		// prepare query statement
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
		// execute query
		$stmt->execute();
		return $stmt;
    }
    
	function readAllMatchsUser($id_user){
		// select all query
		$query = "SELECT (SELECT name FROM users WHERE id = id_user_a), (SELECT name FROM users WHERE id = id_user_B), gol_a, gol_b FROM matchs WHERE id_tournament =? AND id_user_a =? OR id_user_B =?";
		// prepare query statement
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
		$stmt->bindParam(2, $this->$id_user);
		$stmt->bindParam(2, $this->$id_user);
		// execute query
		$stmt->execute();
		return $stmt;
	}
}


 
