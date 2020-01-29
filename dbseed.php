<?php
require 'bootstrap.php';

$statement = <<<EOS
  CREATE TABLE IF NOT EXISTS Users(
    user_id INT NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
	created_on TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id),
	UNIQUE(email)
  ) ENGINE=INNODB;

  CREATE TABLE IF NOT EXISTS Links(
    link_id INT NOT NULL AUTO_INCREMENT,
    link_name VARCHAR(100) NOT NULL,
    destination_url VARCHAR(2000) NOT NULL,
    description TEXT NULL,
    visits_count INT NOT NULL DEFAULT 0,
    email VARCHAR(100) NOT NULL,
    PRIMARY KEY (link_id)
  ) ENGINE=INNODB;

  CREATE TABLE IF NOT EXISTS Sessions(
    id INT NOT NULL AUTO_INCREMENT,
    user_email VARCHAR(100) NOT NULL,
    session_id VARCHAR(100) NOT NULL,
    create_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
  ) ENGINE=INNODB;  
EOS;

try{
  $createTable = $db_connection->exec($statement);
  echo "Success!\n";
} catch(\PDOException $e){
  throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

