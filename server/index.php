<?php
require "../bootstrap.php";

use Src\Routes\Api\Users;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE");

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER["REQUEST_METHOD"];

$uri = filter_var($request_uri, FILTER_SANITIZE_URL);
$uri = parse_url($request_uri, PHP_URL_PATH);
$uri = explode('/', $uri);
$endpoint = $uri[2]; 
$item = $uri[3];

// Check that endpoint starts with /v1, else return 404
if ($uri[1] !== 'v1') {
  header("HTTP/1.1 404 Not Found");
}

$user_id = null;

switch($endpoint){
    // case 'auth':
        
  case 'users':
    // Call to /v1/users endpoint must have user_id
    if (strlen($item) > 0) {
      $user_id = (int) $uri[3];
      $req = (new Users($db_connection, $request_method, $user_id))->request();
    } else{
      header('HTTP/1.1 400 Bad Request');
      echo(json_encode(['Message' => 'User id was not found in URI', 'Success' => 
      'false']));
    }
    break;

  // case 'links':
  // case 'routings':
  default:
    header('HTTP/1.1 400 Bad Request'); 
    echo(json_encode(['Message' => 'Invalid URI', 'Success' => 
    'false']));
}
