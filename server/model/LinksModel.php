<?php
namespace Src\Model;

class LinksModel{
  private $db = null;

  public function __construct($db){
    $this->db = $db;
  }

  // Check if link name is already in db. 
  public function checkLinkName($link_name, $user_id){
    $queryStr = "
      SELECT COUNT(1) AS checkLink
      FROM Links 
      WHERE link_name= :link_name
      AND user_id = :user_id
    ";

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute([
      'link_name' => $link_name,
      'user_id' => $user_id
    ]);
    // Returns 0 if link name does not exists
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
    return $result;
  }

  // Create a new golink
  public function createGoLink(Array $golink,  $user_id){
    $queryStr = "
      INSERT INTO Links
          (link_name, destination_url, description, user_id)
      VALUES
          (:link_name, :destination_url, 
          :description, :user_id)
    ";

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute([
      'link_name' => $golink['link_name'],
      'destination_url' => $golink['destination_url'],
      'description' => $golink['description'],
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

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute(['user_id' => $user_id]);
    $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    return $result;
  }

  // Get specific golink
  public function getGoLink($link_name, $user_id){
    $queryStr = "
      SELECT *
      FROM Links
      WHERE user_id = :user_id
      AND link_name = :link_name
    ";

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute(['user_id' => $user_id, 'link_name' => $link_name]);
    $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    return $result;
  }

  // Get newly created golink
  public function getNewGoLink($user_id){
    $queryStr = "
      SELECT *
      FROM Links
      WHERE user_id = :user_id
      ORDER BY link_id DESC
      LIMIT 1;
    ";

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute(['user_id' => $user_id]);
    $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    return $result;
  }
  

  // Update a golink
  public function updateGoLink(Array $golink, $link_name, $user_id){
    $queryStr = "
      UPDATE Links
      SET 
        destination_url = :destination_url,
        description = :description
      WHERE 
        link_name= :link_name
      AND 
        user_id = :user_id
    ";

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute([
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