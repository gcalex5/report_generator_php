<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 9/26/16
 * Time: 12:09 PM
 */

/**
 * Class BookOfBusiness
 */
class BookOfBusiness{
  protected $repID = array();
  protected $dateEM;
  protected $dateEY;
  protected $empArray = array();
  protected $contracts = array();

  /**
   * BookOfBusiness constructor.
   */
  public function __construct(){
    //TODO: switch this to the correct date variable names
    $this->repID = $_POST['empIDS'];
    $this->dateEM = $_POST['dateEM'];
    $this->dateEY = $_POST['dateEY'];
  }

  public function controller($conn){
    $this->setEmpArray(init_report_employee($conn));

    $results = $this->gatherAccountData($conn);

    $top = $this->generateOutput($results);
    return array($top, null);
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

    $query = "SELECT * FROM contracts WHERE( AnnualMWHS > 0 OR Gas_Usage > 0) AND (EndMonth = "
      . $this->getDateEM() . " AND EndYear =" . $this->getDateEY() . ") AND ( ";

    foreach($this->getRepID() as $id){
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

  protected function generateOutput($contracts){
    //TODO: Create a 2D Array Out[Rep][Array of Contracts]
    //TODO: Create an array of Utilities with Contract Totals/usage/fee/total fee
    $outputTop = array(array());

    //Loop contracts
    foreach($contracts as $contract){
      //Assign contract to a rep
      $outputTop[$contract->getRepID()][] = $contract;
    }
    return $outputTop;
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

  /**
   * @return array
   */
  public function getEmpArray()
  {
    return $this->empArray;
  }

  /**
   * @param array $empArray
   */
  public function setEmpArray($empArray)
  {
    $this->empArray = $empArray;
  }

  /**
   * @return array
   */
  public function getContracts()
  {
    return $this->contracts;
  }

  /**
   * @param array $contracts
   */
  public function setContracts($contracts)
  {
    $this->contracts = $contracts;
  }
}