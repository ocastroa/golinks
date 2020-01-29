<?php
require "../bootstrap.php";

use Src\Routes\Api\Users;
use Src\Routes\Api\Links;
use Src\Routes\Api\Routings;
use Src\Routes\Api\Auth;
use Src\Routes\Middleware\AuthMiddleware;

header('Cache-Control: no-cache');
header("Content-Type: application/json; charset=UTF-8");

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER["REQUEST_METHOD"];

$uri = parse_url($request_uri, PHP_URL_PATH);
$uri = explode('/', $uri);
$endpoint = $uri[2]; 

// Check that endpoint starts with /v1, else return 404
if ($uri[1] !== 'v1') {
  header("HTTP/1.1 400 Bad Request");
  echo(json_encode(['Message' => 'Make sure that endpoint starts with /v1', 'Success' => 
  'false']));
}

switch($endpoint){
  case 'auth':
    // User is logging out
    if(isset($uri[3])){
      if($uri[3] != 'logout'){
        header('HTTP/1.1 400 Bad Request'); 
        echo(json_encode(['Message' => 'Invalid URI', 'Success' => 
        'false']));
      }
      Auth::logOutUser($client, $db_connection);
    }
    else{
      Auth::logInUser($client, $db_connection);
    }
    break;
        
  case 'users':
    $user_email= AuthMiddleware::validateSessionCookie($db_connection);
    $req = (new Users($db_connection, $request_method, $user_email))->request();
    break;

  case 'links':
    $user_email= AuthMiddleware::validateSessionCookie($db_connection);
    $link_name = (isset($uri[3])) ? $uri[3] : null;
    $req = (new Links($db_connection, $request_method, $user_email, $link_name))->request();
    break;

  case 'routings':
    // redirect user to destination url
    $user_email= AuthMiddleware::validateSessionCookie($db_connection);
    $link_name = (isset($uri[3])) ? $uri[3] : null;
    $req = (new Routings($db_connection, $request_method, 
              $user_email, $link_name))->request();
    break;
  
  default:
    header('HTTP/1.1 400 Bad Request'); 
    echo(json_encode(['Message' => 'Invalid URI', 'Success' => 
    'false']));
}
