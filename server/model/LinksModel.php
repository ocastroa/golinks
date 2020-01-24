<?php
/* 
 * Handles db related operations for GoLinks
 * Each link is mapped to a particular user, so user_id is needed to get the correct golink for a user.
*/

class LinksModel{
  private $db = null;

  public function __construct($db){
    $this->db = db;
  }

  // Check if link name is already in db. 
  // Call before creating, updating or deleting golinks
  public function checkLinkName($link_name, $user_id){
    $queryStr = "
      SELECT COUNT(1) 
      FROM Links 
      WHERE link_name= :link_name
      AND user_id = :user_id
    ";

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute([
      'link_name' => $link_name,
      'user_id' => $user_id
    ]);
    // Returns 1 if link name exists, else returns 0
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
  }

  // Create a new golink
  public function createGoLink(Array $golink,  $user_id){
    $queryStr = "
      INSERT INTO Links
          (link_name, destination_url, description, visits_count, user_id)
      VALUES
          (:link_name, :destination_url, 
          :description, :visits_count, :user_id)
    ";

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute([
      'link_name' => $golink['link_name'],
      'destination_url' => $golink['destination_url'],
      'description' => $golink['description'],
      'visits_count' => 0, // default value
      'user_id' => $user_id
    ]);
    $result = $stmt->rowCount();
    return $result;
  }

  // Get all golinks
  public function getAllGoLinks($user_id){
    $queryStr = "
      SELECT *
      FROM Links
      WHERE user_id = :user_id
    ";

    $stmt = $this->db->query($queryStr);
    $stmt->execute(['user_id' => $user_id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  // Update a golink
  public function updateGoLink(Array $golink, $link_name, $user_id){
    $queryStr = "
      UPDATE Links
      SET 
        link_name = :link_name,
        destination_url = :destination_url,
        description = :description
      WHERE 
        link_name= :link_name
      AND 
        user_id = :user_id
    ";

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute([
      'link_name' => $golink['link_name'],
      'destination_url' => $golink['destination_url'],
      'description' => $golink['description'],
      'link_name' => $link_name,
      'user_id' => $user_id
    ]);
    $result = $stmt->rowCount();
    return $result;
  }

  // Delete a golink
  public function deleteGoLink($link_name, $user_id){
    $queryStr = "
      DELETE FROM Links
      WHERE link_name = :link_name
      AND user_id = :user_id
    ";

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute([
      'link_name' => $link_name,
      'user_id' => $user_id
    ]);
    $result = $stmt->rowCount();
    return $result;
  }  
}