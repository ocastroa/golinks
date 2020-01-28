<?php
namespace Src\Model;

// Handles db related operations for Links
class LinksModel{
  private $db = null;

  public function __construct($db){
    $this->db = $db;
  }

  // Check if link name is already in db. 
  public function checkLinkName($link_name, $email){
    $queryStr = "
      SELECT 
        COUNT(1) AS checkLink
      FROM 
        Links 
      WHERE 
        link_name= :link_name
      AND 
        email = :email
    ";

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute([
      'link_name' => $link_name,
      'email' => $email
    ]);
    // Returns 0 if link name does not exists
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
    return $result;
  }

  // Create a new golink
  public function createGoLink(Array $golink,  $email){
    $queryStr = "
      INSERT INTO Links
          (link_name, destination_url, description, email)
      VALUES
          (:link_name, :destination_url, 
          :description, :email)
    ";

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute([
      'link_name' => $golink['link_name'],
      'destination_url' => $golink['destination_url'],
      'description' => $golink['description'],
      'email' => $email
    ]);
    $result = $stmt->rowCount();
    return $result;
  }

  // Get all golinks
  public function getAllGoLinks($email){
    $queryStr = "
      SELECT *
      FROM 
        Links
      WHERE
       email = :email
    ";

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute(['email' => $email]);
    $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    return $result;
  }

  // Update a golink
  public function updateGoLink(Array $golink, $link_name, $email){
    $queryStr = "
      UPDATE 
        Links
      SET 
        destination_url = :destination_url,
        description = :description
      WHERE 
        link_name= :link_name
      AND 
        email = :email
    ";

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute([
      'destination_url' => $golink['destination_url'],
      'description' => $golink['description'],
      'link_name' => $link_name,
      'email' => $email
    ]);
    $result = $stmt->rowCount();
    return $result;
  }

  // Delete a golink
  public function deleteGoLink($link_name, $email){
    $queryStr = "
      DELETE FROM 
        Links
      WHERE 
        link_name = :link_name
      AND 
        email = :email
    ";

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute([
      'link_name' => $link_name,
      'email' => $email
    ]);
    $result = $stmt->rowCount();
    return $result;
  }  

  // Get specific golink
  public function getGoLink($link_name, $email){
    $queryStr = "
      SELECT *
      FROM 
        Links
      WHERE 
        link_name= :link_name
      AND 
       email = :email
    ";

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute(['link_name' => $link_name, 'email' => $email,]);
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
    return $result;
  }

  // Get newly created golink
  public function getNewGoLink($email){
    $queryStr = "
      SELECT *
      FROM 
        Links
      WHERE 
        email = :email
      ORDER BY 
        link_id DESC
      LIMIT 1;
    ";

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute(['email' => $email]);
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
    return $result;
  }
  
  // Get destination url for golink
  public function getDestinationUrl($link_name, $email){
    $queryStr = "
      SELECT 
        destination_url
      FROM 
        Links
      WHERE 
        link_name= :link_name
      AND 
        email = :email
    ";

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute(['link_name' => $link_name, 'email' => $email]);
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
    return $result;
  }

  // Increase the number of redirects for a golink
  public function increaseVisitsCount($link_name, $email){
    $queryStr = "
      UPDATE 
        Links
      SET 
        visits_count = visits_count + 1
      WHERE 
        link_name= :link_name
      AND 
        email = :email
    ";

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute([
      'link_name' => $link_name,
      'email' => $email
    ]);
    $result = $stmt->rowCount();
    return $result;
  }
}
