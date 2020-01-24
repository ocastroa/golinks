<?php
namespace Src\Routes\Api;
use  Src\Model\UsersModel;

class Users{
  private $db = null;
  private $req_method = null;
  private $user_id = null;
  private $user_model = null;

  public function __construct($db, $req_method, $user_id){
    $this->db = $db;
    $this->req_method = $req_method;
    $this->user_id = $user_id;

    $this->user_model = new UsersModel($db);
  }

  public function request(){
    switch ($this->req_method) {
      case 'GET':
        $response = $this->getUser($this->user_id);
        break;
      case 'DELETE':
        $response = $this->deleteUser($this->user_id);
        break;
      default:
        $response = $this->notFoundResponse();
        break;
    }

    header($response['status_code_header']);
    if ($response['body']) {
      echo $response['body'];
    }
  }

  /*
  @route   GET v1/users/:user_id
  @desc    Get a user by id
  @access  Public
  */
  // Need jwt token to complete transaction. Call AuthMiddleware to verify jwt
  public function getUser($user_id){
    $result =  $this->user_model->getUser($user_id);
    if (!$result) {
      return $this->notFoundResponse();
    }

    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($result);
    return $response; 
  }

  /*
    @route   DELETE v1/users/:user_id
    @desc    Delete a user by id
    @access  Public
  */
  // Need jwt token to complete transaction. Call AuthMiddleware to verify jwt
  private function deleteUser($id){
    $result =  $this->user_model->getUser($user_id);
    if (!$result) {
      return $this->notFoundResponse();
    }

    $this->user_model->deleteUser($id);

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
