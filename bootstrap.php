<?php
require 'vendor/autoload.php';

use Src\Config\DbConnector;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$db_connection = (new DbConnector()) -> getConnection();
