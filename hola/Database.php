<?php
class Database {
    private $host = "185.232.14.52";
    private $db_name = "u760464709_pancho_bd";
    private $username = "u760464709_pancho_usr";
    private $password = "Mu;Kf&b2oC3$";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
