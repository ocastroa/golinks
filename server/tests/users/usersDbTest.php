<?php
use  Src\Model\UsersModel;
require '../../../bootstrap.php';

$db = new UsersModel($db_connection);
$id = 3;

// Get user
// var_export($db->getUser($id));

// Add a user
// $addNewUser = [
//     "first_name" => "Michael", 
//     "last_name" => "Jordan", 
//     "email" => "mjordan@gmail.com"
// ];

// var_export($db->addUser($addNewUser));

// Delete user
// var_export($db->deleteUser($user_id));
