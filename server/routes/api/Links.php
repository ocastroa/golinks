<?php
namespace Src\Routes\Api;
use Src\Model\LinksModel;

class Links{
  private $db = null;
  private $req_method = null;
  private $user_email = null;
  private $link_name = null;
  private $links_model = null;

  public function __construct($db, $req_method, $user_email, $link_name){
    $this->db = $db;
    $this->req_method = $req_method;
    $this->user_email = $user_email;
    $this->link_name = $link_name; 

    $this->links_model = new LinksModel($db);
  }

  public function request(){
    switch ($this->req_method) {
      case 'GET':
        $response = $this->getAllGoLinks($this->user_email);
        break;
      case 'POST':
        $response = $this->createGoLink($this->user_email);
        break;     
      case 'PUT':
        // Link name must be present in URI
        if(!isset($this->link_name)){
          $response = $this->badRequest();  
          break;
        }

        $response = $this->updateGoLink($this->link_name, $this->user_email);
        break;      
      case 'DELETE':
        if(!isset($this->link_name)){
          $response = $this->badRequest();  
          break;
        }

        $response = $this->deleteGoLink($this->link_name, $this->user_email);
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
      echo($response['body']);
    }
  }

  /*
    @route   GET v1/links
    @desc    Get all links user has created
    @access  Private
  */
  public function getAllGoLinks($user_email){
    $result = $this->links_model->getAllGoLinks($user_email);

    // $result will have an empty body if user has not created any golinks
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($result);
    return $response; 
  }

  /*
    @route   POST v1/links
    @desc    Create a new golink for a user
    @access  Private
  */
  private function createGoLink($user_email){
    $golink = (array) json_decode(file_get_contents('php://input'), TRUE);

    // Check that link name is included in the payload
    if(strlen($golink['link_name'] ) < 1 || !is_string( $golink['link_name'])) {
      return $this->badRequest();
    }
    
    // Check the other fields in the payload
    if(!$this->checkPayload($golink)){
      return $this->badRequest();
    };

    // Check if user is already using that link name
    $doesLinkNameExist = $this->links_model->checkLinkName($golink["link_name"], $user_email);

    // Links name already exists for user
    if($doesLinkNameExist['checkLink'] == 1){
      $response['status_code_header'] = 'HTTP/1.1 409 Conflict';
      $response['body'] = json_encode(['Message' => 'Link name already exists. No duplicates allowed.', 'Success' => 
      'false']);
      return $response;
    }

    $this->links_model->createGoLink($golink, $user_email);

    // Return newly created golink
    $result = $this->links_model->getNewGoLink($user_email);

    $response['status_code_header'] = 'HTTP/1.1 201 Created';
    $response['body'] = json_encode($result);
    return $response; 
  }

  /*
    @route   PUT v1/links/:link_name
    @desc    Update a golink for a user
    @access  Private
  */
  private function updateGoLink($link_name, $user_email){  
    $golink = (array) json_decode(file_get_contents('php://input'), TRUE);

    // Check that payload has the necessary data before adding to db
    if(!$this->checkPayload($golink)){
      return $this->badRequest();
    };

    // Check if link name exists
    $doesLinkNameExist = $this->links_model->checkLinkName($link_name, $user_email);

    // Links name does not exist, redirect user to their dashboard
    if($doesLinkNameExist['checkLink'] == 0){
      echo("redirecting to user's dashboard");
      exit();    
    }

    $this->links_model->updateGoLink($golink, $link_name, $user_email);
  
    // Return updated resource
    $result = $this->links_model->getGoLink($link_name, $user_email);

    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($result);
    return $response; 
  }
  
  /*
    @route   DELETE v1/links/:link_name
    @desc    Delete a golink
    @access  Private
  */
  private function deleteGoLink($link_name, $user_email){
    $this->links_model->deleteGoLink($link_name, $user_email);
  
    $response['status_code_header'] = 'HTTP/1.1 204 OK';
    $response['body'] = null;
    return $response;
  }

  /* 
   * Check that payload for creating and updating requests
   * are not empty and that they are strings. Link name cannot 
   * be modified, only destination url and description.
  */
  private function checkPayload(&$golink){
    $destination_url = $golink['destination_url'];
    $description = $golink['description'];

    if(strlen($destination_url) < 1){
      return false;
    }

    if(!is_string($destination_url) || !is_string($description)){
      return false;
    }

    return $this->sanitizeData($golink);
  }

  // Sanitize payload fields
  private function sanitizeData(&$golink){
    if(isset($golink['link_name'])){
      $golink['link_name'] = filter_var($golink['link_name'], FILTER_SANITIZE_STRING);

      // remove anything that's not a letter 
      $golink['link_name'] = preg_replace("/[^a-zA-Z]/", "", $golink['link_name']);

      // lowercase link name
      $golink['link_name'] = strtolower( $golink['link_name'] );
    }

    $golink['destination_url'] = filter_var($golink['destination_url'],FILTER_SANITIZE_URL);

    // invalid url
    if(filter_var($golink['destination_url'] , FILTER_VALIDATE_URL) == false){
      return false;
    }
    
    $golink['description'] = filter_var($golink['description'] , FILTER_SANITIZE_STRING);

    return true;
  }
  
  // Payload does not have all the necessary data, return 400
  private function badRequest()
  {
    $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
    $response['body'] = json_encode(['Message' => 'Make sure the payload fields and the URI path are correct.', 'Success' => 
    'false']);
    return $response;
  }    
}
