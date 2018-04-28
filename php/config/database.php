<?php
include dirname(__FILE__) . '/../utils/LogUtil.php';
class Database
{
    private $TAG = 'Config.Tournament';
    // specify your own database credentials
    private $host = 'localhost';
    private $db_name = 'fifa_db';
    private $username = 'fifa';
    private $password = 'pepe1122';
    public $conn;

    // get the database connection
    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO('mysql:host='.$this->host.';dbname='.$this->db_name, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $exception) {
            log_error($this->TAG, 'Connection error: '.$exception->getMessage());
        }
        return $this->conn;
    }
}
