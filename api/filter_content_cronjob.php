<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// include database and object files
include_once './config/database.php';
include_once './objects/filter_content.php';

 
// get database connection
$database = new Database();
$db = $database->getConnection();
$push = new Filter_content($db);


$row=$push->processFilterContent();
 
  ?>