<?php
namespace Src\Model;

/**
 * NOTE: The web server can be configured to keep track of user sessions. To do so in Apache, you must use the submodule 'mod_session' which stores the user sessions in a SQL database using the  module 'mod_dbd', which must be configured first. To learn more about configuring the Apache web server, visit 'https://httpd.apache.org/docs/2.4/mod/mod_session_dbd.html'.
 */

// Handles db related operations for Sessions
class SessionsModel{
  private $db;

  public function __construct($db){
    $this->db = $db;
  }
  
  public function addNewSession($session_id, $user_email){
    $queryStr = "
      INSERT INTO Sessions
        (session_id, user_email)
      VALUES
        (:session_id, :user_email)
    ";

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute([
      'session_id' => $session_id,
      'user_email' => $user_email,
    ]);
    $result = $stmt->rowCount();
    return $result;
  }

  public function deleteSession($session_id){
    $queryStr = "
      DELETE FROM 
        Sessions
      WHERE 
       session_id = :session_id
    ";

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute(['session_id' => $session_id]);
    $result = $stmt->rowCount();
    return $result;
  }

  public function getUsersEmail($session_id){
    $queryStr = "
      SELECT 
        user_email
      FROM 
        Sessions
      WHERE 
        session_id = :session_id
    ";

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute(['session_id' => $session_id]);
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
    return $result;
  }
}