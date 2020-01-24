<?php
namespace Src\Model;

// Handles db related operations for Users

class UsersModel{
  private $db = null;

  public function __construct($db){
    $this->db = $db;
  }

  // Check if user is already in db
  // Call before adding user and before deleting user
  public function checkUser($email){
    $queryStr = "
      SELECT COUNT(1) AS isUserFound
      FROM Users
      WHERE email= :email
    ";

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute(['email' => $email]);
    // Returns 1 if user exists, else returns 0
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);

    return $result;
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

  // Get all users from db
  public function getAllUsers(){
    $queryStr = "
      SELECT *
      FROM Users
    ";

    $stmt = $this->db->query($queryStr);
    $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    return $result;
  }

  // Get specific user from db
  public function getUser($email){
    $queryStr = "
      SELECT *
      FROM Users
      WHERE email = :email
    ";

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute(['email' => $email]);
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
    return $result;
  }

  // Delete user from db
  public function deleteUser($email){
    $queryStr = "
      DELETE FROM Users
      WHERE email = :email
    ";

    $stmt = $this->db->prepare($queryStr);
    $stmt->execute(['email' => $email]);
    $result = $stmt->rowCount();
    return $result;
  }
}