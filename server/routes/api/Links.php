<?php
namespace Src\Routes\Api;
use Src\Model\LinksModel;

class Links{
  private $db = null;
  private $req_method = null;
  private $user_id = null;
  private $link_name = null;
  private $links_model = null;

  public function __construct($db, $req_method, $user_id, $link_name){
    $this->db = $db;
    $this->req_method = $req_method;
    $this->user_id = $user_id;
    $this->link_name = $link_name; 

    $this->links_model = new LinksModel($db);
  }

  public function request(){
    switch ($this->req_method) {
      case 'GET':
        $response = $this->getAllGoLinks($this->user_id);
        break;
      case 'POST':
        $response = $this->createGoLink($this->user_id);
        break;     
      case 'PUT':
        $response = $this->updateGoLink($this->link_name, $this->user_id);
        break;      
      case 'DELETE':
        $response = $this->deleteGoLink($this->link_name, $this->user_id);
        break;
      default:
        $response = $this->notFoundResponse();
        break;
    }

    header($response['status_code_header']);
    if ($response['body']) {
      echo($response['body']);
    }
  }

  /*
    @route   GET v1/links
    @desc    Get all links user has created
    @access  Public
  */
  public function getAllGoLinks($user_id){
    $result = $this->links_model->getAllGoLinks($user_id);

    // $result will have an empty body if user has not created any golinks
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($result);
    return $response; 
  }

  /*
    @route   POST v1/links
    @desc    Create a new golink for a user
    @access  Public
  */
  private function createGoLink($user_id){
    $golink = (array) json_decode(file_get_contents('php://input'), TRUE);

    // Check that payload has the necessary data before adding to db
    if(!$this->checkPayload("create", $golink)){
      return $this->badRequest();
    };

    // Check if user is already using that link name
    $doesLinkNameExist = $this->links_model->checkLinkName($golink["link_name"], $user_id);

    // Links name already exists for user
    if($doesLinkNameExist['checkLink'] == 1){
      $response['status_code_header'] = 'HTTP/1.1 409 Conflict';
      $response['body'] = json_encode(['Message' => 'Link name already exists. No duplicates allowed.', 'Success' => 
      'false']);
      return $response;
    }

    $this->links_model->createGoLink($golink, $user_id);

    // Return newly created golink
    $result = $this->links_model->getNewGoLink($user_id);

    $response['status_code_header'] = 'HTTP/1.1 201 Created';
    $response['body'] =  json_encode($result);
    return $response; 
  }

  /*
    @route   PUT v1/links/:user_id
    @desc    Update a golink for a user
    @access  Public
  */
  private function updateGoLink($link_name, $user_id){  
    $golink = (array) json_decode(file_get_contents('php://input'), TRUE);

    if(!$this->checkPayload("update", $golink)){
      return $this->badRequest();
    };

    // Check if link name exists
    $doesLinkNameExist = $this->links_model->checkLinkName($link_name, $user_id);

    // Links name does not exist, return 404
    if($doesLinkNameExist['checkLink'] == 0){
      return $this->notFoundResponse();       
    }

    $this->links_model->updateGoLink($golink, $link_name, $user_id);
  
    // Return updated resource
    $result = $this->links_model->getGoLink($link_name, $user_id);

    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($result);
    return $response; 
  }
  
  /*
    @route   DELETE v1/links/:user_id
    @desc    Delete a golink
    @access  Public
  */
  private function deleteGoLink($link_name, $user_id){
    $this->links_model->deleteGoLink($link_name, $user_id);
  
    $response['status_code_header'] = 'HTTP/1.1 204 OK';
    $response['body'] = null;
    return $response;
  }

  /* 
   * Check that payload for creating and updating requests
   * are not empty and that they are strings  
  */
  private function checkPayload($requestType, $golink){
    // Only "create" request has link_name in payload
    $link_name = ($requestType == "create") ?  $golink['link_name'] : null;
    $destination_url = $golink['destination_url'];
    $description = $golink['description'];

    switch($requestType){
      case "create":
        // description is optional when creating golink        
        if(strlen($link_name) < 1 || strlen($destination_url) < 1){
          return false;
        }

        if(!is_string($link_name) || !is_string($destination_url) || !is_string($description)){
          return false;
        }

        return true;
        break;

      case "update":
        // Description can be empty, but not destination url
        if(strlen($destination_url) < 1){
          return false;
        }

        if(!is_string($destination_url) || !is_string($description)){
          return false;
        }

        return true;
        break;
    }
  }
  
  // Payload does not have all the necessary data, return 400
  private function badRequest()
  {
    $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
    $response['body'] = json_encode(['Message' => 'Check that payload fields are correct.', 'Success' => 
    'false']);
    return $response;
  }  

  // Resource not found, return 404
  private function notFoundResponse()
  {
    $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
    return $response;
  }  
}
