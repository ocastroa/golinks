<?php
require '../../../bootstrap.php';

$statement = <<<EOS
    INSERT INTO Links
        (link_name, destination_url, description, user_id)
    VALUES
        ('git', 'https://github.com/ocastroa/', 'My git repo', 2),
        ('dev', 'https://dev.to/', 'Dev.to home page', 1),
        ('linkedin', 'https://www.linkedin.com/in/ocastroa/', '', 2);
EOS;

$createTable = $db_connection->exec($statement);
echo "Success!\n";
