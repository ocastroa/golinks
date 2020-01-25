<?php
require "../bootstrap.php";

use Src\Routes\Api\Users;
use Src\Routes\Api\Links;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE");
// $token = header("x-auth-token");
// call authmiddleware to verify jwt, convert userid to int in middleware
$jwt_userid = 1; // delete after implementing jwt

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER["REQUEST_METHOD"];

$uri = filter_var($request_uri, FILTER_SANITIZE_URL);
$uri = parse_url($request_uri, PHP_URL_PATH);
$uri = explode('/', $uri);
$endpoint = $uri[2]; 
// $item = $uri[3];

// Check that endpoint starts with /v1, else return 404
if ($uri[1] !== 'v1') {
  header("HTTP/1.1 404 Not Found");
}

$user_id = null;

switch($endpoint){
    // case 'auth':
        
  case 'users':
    // get user_id from jwt => call authmiddleware
    $user_id = $jwt_userid;
    $req = (new Users($db_connection, $request_method, $user_id))->request();
    break;

  case 'links':
    // get user_id from jwt => call authmiddleware
    $user_id = $jwt_userid;
    $link_name = (isset($uri[3])) ? $uri[3] : null;
    $req = (new Links($db_connection, $request_method, $user_id, $link_name))->request();
    break;

  // case 'links':
    // get user_id from jwt => call authmiddleware
  // case 'routings':
  default:
    header('HTTP/1.1 400 Bad Request'); 
    echo(json_encode(['Message' => 'Invalid URI', 'Success' => 
    'false']));
}
