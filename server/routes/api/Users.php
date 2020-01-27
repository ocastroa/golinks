<?php
namespace Src\Routes\Api;
use Src\Model\UsersModel;

class Users{
  private $db = null;
  private $req_method = null;
  private $user_email = null;
  private $user_model = null;

  public function __construct($db, $req_method, $user_email){
    $this->db = $db;
    $this->req_method = $req_method;
    $this->user_email = $user_email;

    $this->user_model = new UsersModel($db);
  }

  public function request(){
    switch ($this->req_method) {
      case 'GET':
        $response = $this->getUser($this->user_email);
        break;
      case 'DELETE':
        $response = $this->deleteUser($this->user_email);
        break;
      default:
        $failure_msg = json_encode(['Message' => "Method {$this->req_method} is not allowed for this resource", 'Success' => 
        'false']);
        
        header('HTTP/1.1 405 Method Not Allowed');
        echo($failure_msg);
        exit();
    }

    header($response['status_code_header']);
    if ($response['body']) {
      echo $response['body'];
    }
  }

  /*
    @route   GET v1/users
    @desc    Get user's information
    @access  Public
  */
  private function getUser($user_email){
    $result =  $this->user_model->getUser($user_email);
    if (!$result) {
      return $this->notFoundResponse();
    }

    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($result);
    return $response; 
  }

  /*
    @route   DELETE v1/users
    @desc    Delete a user
    @access  Public
  */
  private function deleteUser($user_email){
    $result =  $this->user_model->getUser($user_email);
    if (!$result) {
      return $this->notFoundResponse();
    }

    $this->user_model->deleteUser($user_email);

    $response['status_code_header'] = 'HTTP/1.1 204 OK';
    $response['body'] = null;
    return $response;
  }

  // Resource not found, return 404
  private function notFoundResponse()
  {
    $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
    return $response;
  }  
}
