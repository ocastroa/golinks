<?php
namespace Src\Model;

// Handles db related operations for Users
class UsersModel{
  private $db;

  public function __construct($db){
    $this->db = $db;
  }

  public function addUser(Array $user){
    $queryStr = "
      INSERT INTO Users
          (first_name, last_name, email)
      VALUES
          (:first_name, :last_name, :email)
    ";

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute([
      'first_name' => $user['first_name'],
      'last_name' => $user['last_name'],
      'email' => $user['email']
    ]);
    $result = $stmt->rowCount();
    return $result;
  }

  // Get specific user from db
  public function getUser($user_id){
    $queryStr = "
      SELECT *
      FROM 
        Users
      WHERE 
        user_id = :user_id
    ";

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute(['user_id' => $user_id]);
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
    return $result;
  }

  // Delete user from db
  public function deleteUser($user_id){
    $queryStr = "
      DELETE FROM 
        Users
      WHERE 
        user_id = :user_id
    ";

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute(['user_id' => $user_id]);
    $result = $stmt->rowCount();
    return $result;
  }
}