<?php
require '../../../bootstrap.php';

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
EOS;

$createTable = $db_connection->exec($statement);
echo "Success!\n";
