<?php
namespace Src\Routes\Api;
use Src\Model\LinksModel;

class Routings{
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
    if($this->req_method == 'GET'){
      $response = $this->redirect($this->user_email, $this->link_name);
    }

    else{
      $failure_msg = json_encode(['Message' => "Method {$this->req_method} is not allowed for this resource", 'Success' => 
      'false']);
      
      header('HTTP/1.1 405 Method Not Allowed');
      echo($failure_msg);
      exit();
    }
  }

  /*
    @route   GET /v1/routings/:link_name
    @desc    Redirect user to destination url
    @access  Public
  */
  private function redirect($user_email, $link_name){
    // Check if link name exists
    $doesLinkNameExist = $this->links_model->checkLinkName($link_name, $user_email);

    // Links name does not exist or was not entered
    if($doesLinkNameExist['checkLink'] == 0 || !isset($link_name)){
      // redirect to user's dashboard  
    }

    $destination_url = $this->links_model->getDestinationUrl($link_name, $user_email);

    header('HTTP/1.1 302 Found');
    header('Status: 302');
    header("Location: {$destination_url['destination_url']}");
    exit();
  }
}