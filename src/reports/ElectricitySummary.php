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
  protected $empArray = array();
  protected $contracts = array();

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
    //Initialize an array of employees
    $this->initEmps($conn);

    //Pull the contracts and initialize an array of them
    $results = $this->gatherAccountData($conn);

    //Calculate the totals AnnualMWHs(% and mWh) AnnualTotals($ and %)
    $this->calculateTotals($results);

    //Clean up empty employees for output
    $this->empArrayCleanUp();

    return $this->getEmpArray();
  }

  /**
   * Initialize an array of Employees for the requested report
   *
   * @param $conn -> Passed in mysqli connection
   */
  protected function initEmps($conn){
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

    if(isset($empArray)){
      $this->setEmpArray($empArray);
    }
  }

  /**
   * Gather the account data and parse it into an array of contract objects
   *
   * @param $conn -> Passed in mysqli connection
   * @return array -> return array of contract objects
   */
  //TODO: Exclude Gas Contracts
  protected function gatherAccountData($conn){
    $x = 0;
    $contracts = array();
    $query = "SELECT * FROM contracts WHERE( AnnualMWHS > 0 AND EndMonth = "
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

  /**
   * Calculate the totals an set them to the Employee array
   * Percentages/Fee/mWh's
   * Totals: Overall total, Renewed, Working, Back, and Lost
   *
   * @param $contracts -> Passed in array of contracts we are working with
   */
  //TODO: Add a 'total' employee
  protected function calculateTotals($contracts){
    foreach($contracts as $contract){
      //Grab the Employee and set it to rep
      $rep = $this->getEmpArray()[$contract->getRepID()];
      //Calculate the totals on the contract
      if(isset($rep)){
        $rep->setMwhTotal($rep->getMwhTotal() + $contract->getAnnualMwhs());
        $rep->setFeeTotal($rep->getFeeTotal() +
          ($contract->getAnnualMWHs() * $contract->getMils()));

        //Set and append appropriate totals
        //Total Fee = Fee + (Mils * Annual mWh's)
        if(isset($contract)){
          if($contract->getRenewalStatusID() == 8){
            $rep->setFeeRenewed($rep->getFeeRenewed() +
              ($contract->getAnnualMWHs() * $contract->getMils()));
            $rep->setMwhRenewed($rep->getMwhRenewed() + $contract->getAnnualMWHs());
          }
          elseif($contract->getRenewalStatusID() == 9
            || $contract->getRenewalStatusID() == 11){
            $rep->setFeeWorking($rep->getFeeWorking() +
              ($contract->getAnnualMWHs() + $contract->getMils()));
            $rep->setMwhWorking($rep->getMwhWorking() + $contract->getAnnualMWHs());
          }
          elseif($contract->getRenewalStatusID() == 1){
            $rep->setFeeBack($rep->getFeeBack() +
              ($contract->getAnnualMWHs() + $contract->getMils()));
            $rep->setMwhBack($rep->getMwhBack() + $contract->getAnnualMWHs());
          }
          else{
            $rep->setFeeLost($rep->getFeeLost() +
              ($contract->getAnnualMWHs() + $contract->getMils()));
            $rep->setMwhLost($rep->getMwhLost() + $contract->getAnnualMWHs());
          }
        }
        //Set the rep back into the employee array
        $this->getEmpArray()[$contract->getRepID()] = $rep;
      }
      else{
        //TODO: Do something here, handle non-existent rep id's
      }
    }
  }

  /**
   *
   */
  protected function empArrayCleanUp(){
    foreach($this->getEmpArray() as $rep){
      if($rep->getMwhTotal() == 0 && $rep->getFeeTotal() == 0){
        unset($this->empArray[$rep->getId()]);
      }
    }
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
}