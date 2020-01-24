<?php
namespace Src\Config;

// Connect to db
class DbConnector{
    private $dbConnection = null;

    public function __construct(){       
        $host = getenv('DB_HOST');
        $db = getenv('DB_DATABASE');
        $user = getenv('DB_USER');
        $pwd = getenv('DB_PASSWORD');
        
        $charset = 'utf8mb4';
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

        try{
            $this->dbConnection = new \PDO($dsn, $user, $pwd);
        } catch(\PDOException $e){
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getConnection(){
        return $this->dbConnection;   
    }
}

