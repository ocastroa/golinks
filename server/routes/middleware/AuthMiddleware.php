<?php
namespace Src\Routes\Middleware;
use Src\Model\SessionsModel;

class AuthMiddleware{
  public static function validateSessionCookie($db){    
    if(!isset($_COOKIE['PHPSESSID'])){
      header('HTTP/1.1 403 Forbidden');
      echo("redirecting to login page");
      exit();
    }

    else{
      $sessions_model = new SessionsModel($db);
      $result = $sessions_model->getUsersEmail($_COOKIE["PHPSESSID"]);
      
      return $result["user_email"];  
    }
  }
}
