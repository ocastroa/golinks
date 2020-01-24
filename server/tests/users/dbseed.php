<?php
require '../../../bootstrap.php';

$statement = <<<EOS
    INSERT INTO Users
        (user_id, first_name, last_name, email)
    VALUES
        (1, 'Jordan', 'Smith', 'jsmith@gmail.com'),
        (2, 'John', 'Mayer', 'jmayer@gmail.com'),
        (3, 'Saul', 'Hudson', 'shudson@gmail.com'),
        (4, 'Richard', 'Feynman', 'rfeynman@gmail.com');
EOS;

$createTable = $dbConnection->exec($statement);
echo "Success!\n";
