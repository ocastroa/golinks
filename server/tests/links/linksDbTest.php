<?php
use  Src\Model\LinksModel;
require '../../../bootstrap.php';

$db = new LinksModel($db_connection);
$user_id = 2;
$link_name = 'git';

// Check if link name exists
$check_link_name = var_export($db->checkLinkName($link_name, $user_id));

// Create new golink
// $golink = [
//   "link_name" => "leet", 
//   "destination_url" => "https://leetcode.com/problemset/all/", 
//   "description" => "LeetCode homepage"
//   ];

//   var_export($db->createGoLink($golink, $user_id));


// Get all links from user
// var_export($db->getAllGoLinks($user_id));

// Update golink from user
// $golink = [
//   "destination_url" => "https://dev.to/", 
//   "description" => "dev.to home page"
//   ];

//   var_export($db->updateGoLink($golink, $link_name, $user_id));

// Delete golink 
// var_export($db->deleteGoLink($link_name, $user_id));