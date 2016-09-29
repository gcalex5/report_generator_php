<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 9/26/16
 * Time: 12:08 PM
 */

/**
 * Class ElectricitySummary
 */
class ElectricitySummary{
  protected $repID = array();
  protected $dateEM;
  protected $dateEY;

  /**
   * ElectricitySummary constructor.
   *
   * Construct the initial object and populate the variables necessary
   * to generate an Electricity Renewal Report
   *
   * Shows all active Contracts ending at the time sorted by Rep/Agent
   */
  public function __construct(){
    //TODO: switch this to the correct date variable names
    $this->repID = $_POST['empIDS'];
    $this->dateEM = $_POST['dateEM'];
    $this->dateEY = $_POST['dateEY'];
  }

  /**
   * @param $conn
   * @return array
   */
  public function controller($conn){
    //Array of Contracts
    $results = $this->gatherAccountData($conn);

    //Calculate the totals AnnualMWHs(% and mWh) AnnualTotals($ and %)
    $calc_totals = $this->calculateTotals($results);

    return $results;
  }

  /**
   * Gather the account data and parse it into an array of contract objects
   *
   * @param $conn -> Passed in mysqli connection
   * @return array -> return array of contract objects
   */
  protected function gatherAccountData($conn){
    $x = 0;
    $contracts = array();
    $query = "SELECT * FROM contracts WHERE( AnnualMWHS > 0 AND EndMonth = "
      . $this->getDateEM() . " AND EndYear =" . $this->getDateEY() . ") AND ( ";
    foreach($this->getRepID() as $id){
      $foo = $id;
      if($x == 0){
        $query.= "RepID = " . $id . " ";
      }
      else{
        $query.= "OR RepID = " . $id . " ";
      }
      $x++;
    }
    $query.= ") ORDER BY EndYear, EndMonth";
    $result = run_query($conn, $query);

    while($row = mysqli_fetch_array($result)){
      $contract = new Contract($row);
      $contracts[] = $contract;
    }

    return $contracts;
  }

  protected function calculateTotals(){

  }

  /**
   * @return array
   */
  public function getRepID()
  {
    return $this->repID;
  }

  /**
   * @param array $repID
   */
  public function setRepID($repID)
  {
    $this->repID = $repID;
  }

  /**
   * @return mixed
   */
  public function getDateEM()
  {
    return $this->dateEM;
  }

  /**
   * @param mixed $dateEM
   */
  public function setDateEM($dateEM)
  {
    $this->dateEM = $dateEM;
  }

  /**
   * @return mixed
   */
  public function getDateEY()
  {
    return $this->dateEY;
  }

  /**
   * @param mixed $dateEY
   */
  public function setDateEY($dateEY)
  {
    $this->dateEY = $dateEY;
  }
}