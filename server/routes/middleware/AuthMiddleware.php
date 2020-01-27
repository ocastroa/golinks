<?php
namespace Src\Routes\Middleware;
use Src\Model\SessionsModel;

class AuthMiddleware{
  public static function validateSessionCookie($db){    
    if(!isset($_COOKIE['PHPSESSID'])){
      $failure_msg = json_encode(['Message' => "Login to get session id", 'Success' => 'false']);
      
      // Might want to redirect user to login page
      
      header('HTTP/1.1 403 Forbidden');
      echo($failure_msg);
      exit();
    }

    else{
      $sessions_model = new SessionsModel($db);
      $result = $sessions_model->getUsersEmail($_COOKIE["PHPSESSID"]);
      
      return $result["user_email"];  
    }
  }
}
