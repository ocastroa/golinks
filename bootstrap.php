<?php
require_once 'vendor/autoload.php';

use Src\Config\DbConnector;
use Dotenv\Dotenv;

// Load environmental variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Initialize connection to db
$db_connection = (new DbConnector()) -> getConnection();

// Initialize connection to Google Client
$client = new Google_Client();
$client->setClientId(getenv('CLIENT_ID'));
$client->setClientSecret(getenv('CLIENT_SECRET'));

$client->addScope('email');
$client->addScope('profile');
