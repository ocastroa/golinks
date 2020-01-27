<?php
namespace Src\Routes\Api;
use Src\Model\SessionsModel;
use Src\Model\UsersModel;

// call Google Client API to get tokens and get user's basic profile info
/* call session function to store user's email in session and send session id to client in cookie. Store session id in Apache webserver db along with username and other details. When user makes a new request, the client will pass the cookie with session id which will be verified with the webserver db. If valid, the user's email will be selected from the session info and will be used for the other endpoints

*/
session_start();

class Auth{
  // Delete session from db
  public static function logUserOut($db){
    // No cookie and session to remove, redirect to login page
    if(!isset($_COOKIE['PHPSESSID'])){
      echo("redirecting to login page");
    }

    $sessions_model = new SessionsModel($db);
    $sessions_model->deleteSession($_COOKIE['PHPSESSID']);

    session_destroy();
    unset($_COOKIE['PHPSESSID']);
    // Delete cookie on client side by setting expiration date to past date
    setcookie('PHPSESSID', '', time()- 3600, '/');

    // redirect user to login page
  }

  public static function getUserProfile($db){
    // get id_token from payload
    // verify id_token
    // get users info i.e. email and name
    
    $user_email = "pgibson@gmail.com";
    $name = "Paul Gibson";
    $name_arr = explode(' ', $name); // Split first name and last name

    $user_model = new UsersModel($db);
    $result = $user_model->getUser($user_email);

    // Add new user to db
    if (!$result) {
      $new_user = [
      'first_name' => $name_arr[0],
      'last_name' => $name_arr[1],
      'email' => $user_email
      ];

      $user_model->addUser($new_user);
    }

    self::startSession($db, $user_email);

    // redirect user to their dashboard

    // put client_id in .env
    // $client = new Google_Client(['client_id' => $CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
    // $payload = $client->verifyIdToken($id_token);
    // if ($payload) {
    //   // get users email and name(parse name to first and last name)
    //   $userid = $payload['sub'];
    //   $user_email = $payload['sub'];
    //   startSession($db, $user_email);
    //   // If request specified a G Suite domain:
    //   //$domain = $payload['hd'];
    // } else {
    //   // Invalid ID token
    // }
  }

  private static function startSession($db, $user_email){    
    $session_id = session_id();
    
    $sessions_model = new SessionsModel($db);
    $sessions_model->addNewSession($session_id, $user_email);
    
    // Send cookie session id to client
    setcookie('PHPSESSID', $session_id, 0, '/');
  }

}
