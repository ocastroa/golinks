<?php
require_once 'vendor/autoload.php';

use Src\Config\DbConnector;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$db_connection = (new DbConnector()) -> getConnection();

$client = new Google_Client();
$client->setClientId(getenv('CLIENT_ID'));
$client->setClientSecret(getenv('CLIENT_SECRET'));

$client->addScope('email');
$client->addScope('profile');
