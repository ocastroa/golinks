<?php
require 'vendor/autoload.php';

use Src\Config\DbConnector;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$dbConnection = (new DbConnector()) -> getConnection();
