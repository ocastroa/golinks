<?php
require '../../../bootstrap.php';

$statement = <<<EOS
  CREATE TABLE IF NOT EXISTS Links(
    link_id INT NOT NULL AUTO_INCREMENT,
    link_name VARCHAR(100) NOT NULL,
    destination_url VARCHAR(2000) NOT NULL,
    description TEXT NULL,
    visits_count INT NOT NULL DEFAULT 0,
    email VARCHAR(100) NOT NULL,
    PRIMARY KEY (link_id)
  ) ENGINE=INNODB;
EOS;

$createTable = $db_connection->exec($statement);
echo "Success!\n";
