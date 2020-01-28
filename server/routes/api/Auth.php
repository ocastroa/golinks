<?php
namespace Src\Routes\Api;
use Src\Model\SessionsModel;
use Src\Model\UsersModel;

session_start();

class Auth{
   /*
    @route   GET v1/auth/logout
    @desc    Log user out and redirect to login page
    @access  Public
  */
  public static function logOutUser($client, $db){
    // No cookie and session to remove, redirect to login page
    if(!isset($_COOKIE['PHPSESSID'])){
      echo("redirecting to login page");
      exit();
    }

    // Delete session from db
    $sessions_model = new SessionsModel($db);
    $sessions_model->deleteSession($_COOKIE['PHPSESSID']);

    // Revoke Google access token
    $client->revokeToken();

    session_destroy();
    unset($_COOKIE['PHPSESSID']);
    
    // Delete cookie on client side by setting expiration date to past date
    setcookie('PHPSESSID', '', time()- 3600, '/');

    // redirect user to login page
    echo("redirecting to login page");
    exit();
  }

  /**
   * NOTE: The following is not the full implementation for Google Sign-In. To see the full implementation, check out the documentation - 'https://developers.google.com/identity/sign-in/web/backend-auth'
   * 
   * Here, we use OAuth 2.0 Playground to quickly get the access token. First, select the 'Google OAuth2 API v2' api and select the 'userinfo.email' and 'userinfo.profile' options. Then, click 'Exchange authorization code for tokens' to get the access token. Include the access token inside the payload, with key name 'access_token', and send a POST request to the endpoint '/v1/auth'. Doing so will call the static function below. 
   * Make sure to first set the values of CLIENT_ID and CLIENT_SECRET in your .env file.
  */

    
  /*
    @route   POST v1/auth
    @desc    Log user in using Google sign-in
    @access  Public
  */
  public static function logInUser($client, $db){
    // Get access token from payload
    $token = (array) json_decode(file_get_contents('php://input'), TRUE);

    // Apply an access token to a new Google_Client object
    $client->setAccessToken($token['access_token']);

    $google_service = new \Google_Service_Oauth2($client);

    //Get user profile
    $payload = $google_service->userinfo->get();
   
    if($payload){
      $user_email = $payload['email'];
      $name = $payload['name'];
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
    } else{
      header('HTTP/1.1 401 Unauthorized');
      echo("redirecting to login page");
      exit();
    }
  }

  private static function startSession($db, $user_email){    
    $session_id = session_id();
    
    $sessions_model = new SessionsModel($db);
    $sessions_model->addNewSession($session_id, $user_email);
    
    // Send cookie session id to client
    setcookie('PHPSESSID', $session_id, 0, '/');
  }
}
