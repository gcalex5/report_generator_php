<?php
/**
 * Handles the various queries needed by the application as well as constructing
 * the initial database connection
 *
 * Created by PhpStorm.
 * User: alex
 * Date: 9/26/16
 * Time: 12:13 PM
 */

/**
 * Constructs the initial database connection
 * is called from 'index.php'. Credentials are gathered from
 * the config file located '/config/database.ini'
 *
 * @return mysqli -> returns a mysqli database connection
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

/**
 * Query a list of representatives ordered alphabetically by first name
 *
 * @param $conn -> Passed in database connection
 * @return array -> Returns array of Employee objects
 */
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

/**
 * Query the date ranges from the 'contracts' table
 *
 * @param $conn -> Passed in database connection
 * @return array -> Returns an array [0]Start Month [1]Start Year [2]End Month [3]End Year
 */
function query_date_ranges($conn){
  $temp = array();
  $querySM = 'SELECT DISTINCT (StartMonth) FROM contracts ORDER BY StartMonth;';
  $querySY ='SELECT DISTINCT (StartYear) FROM contracts ORDER BY StartYear;';
  $queryEM = 'SELECT DISTINCT (EndMonth) FROM contracts ORDER BY EndMonth;';
  $queryEY =  'SELECT DISTINCT (EndYear) FROM contracts ORDER BY EndYear;';
  $temp[] = mysqli_fetch_all($conn->query($querySM));
  $temp[] = mysqli_fetch_all($conn->query($querySY));
  $temp[] = mysqli_fetch_all($conn->query($queryEM));
  $temp[] = mysqli_fetch_all($conn->query($queryEY));
  return $temp;
}

/**
 * Run a generic query and return the result set
 *
 * @param $conn -> Passed in database connection
 * @param $query -> passed in query string
 * @return mysqli_result -> result set from query
 */
function run_query($conn, $query){
  return $conn->query($query);
}

/**
 * Handles the initialization of an array of employees
 * Utilized in Renewal Reports and Book of Business Reports.
 *
 * Current setup relies on $_POST['empIDS'] value being set, should
 * do some error checking regarding that
 * 
 * @param $conn -> Passed in mysqli connection
 * @return array -> Return an array of Employee objects
 */
function init_report_employee($conn){
  $empArray = array();
  if(isset($_POST['empIDS'])){
    $query = 'SELECT ID, First, Last, Title FROM reps WHERE (id=';
    $x=0;
    foreach ($_POST['empIDS'] as $id){
      if($x== 0){
        $query .= $id;
      }
      else{
        $query .= " OR id=". $id . " ";
      }
      $x++;
    }
    $query .= ") ORDER BY First";
    $result = run_query($conn, $query);
    while($row = mysqli_fetch_array($result)){
      $emp = new Employee($row);
      $empArray[$emp->getId()] = $emp;
    }
  }
  else{
    //TODO: Do something if we don't have the proper $_POST data
  }
  return $empArray;
}
