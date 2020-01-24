<?php
use  Src\Model\UsersModel;
require '../../../bootstrap.php';

$db =new UsersModel($dbConnection);
$email = "jmayer@gmail.com";

// Check if user exists; 1 if user exists, else 0
var_export($db->checkUser($email));

// Get user
var_export($db->getUser($email));

// Get all users
var_export($db->getAllUsers());

// Add a user
// $addNewUser = [
//     "first_name" => "Michael", 
//     "last_name" => "Jordan", 
//     "email" => "mjordan@gmail.com"
// ];

// var_export($db->addUser($addNewUser));

// Delete user
// var_export($db->deleteUser("mjordan@gmail.com"));
