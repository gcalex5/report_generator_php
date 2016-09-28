<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 9/26/16
 * Time: 12:13 PM
 */

function init_database(){
  $config_array = parse_ini_file("config/database.ini");
  $user = $config_array['username'];
  $pass = $config_array['password'];
  $url = $config_array['url'];
  $db = $config_array['db'];
  $conn = new mysqli($url, $user, $pass, $db);
  if($conn->connect_error){
    //TODO: have this return an error to 'index.php' and display the database connection issue
    die("Connection failed: " . $conn->connect_error);
  }
  else{
    return $conn;
  }
}

function query_reps($conn){
  $query = 'SELECT ID, First, Last, Title FROM reps ORDER BY First';
  $result = $conn->query($query);
  $reps = array();
  while($row = mysqli_fetch_array($result)){
    $rep = new Employee($row);
    $reps[] = $rep;
  }
  return $reps;
}
