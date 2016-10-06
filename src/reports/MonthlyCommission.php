<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 9/26/16
 * Time: 12:09 PM
 */

/**
 * Class MonthlyCommission
 */
class MonthlyCommission{
  protected $repID = array();
  protected $dateSM;
  protected $dateSY;
  protected $dateEM;
  protected $dateEY;
  protected $empArray = array();
  protected $contracts = array();


  /**
   * MonthlyCommission constructor.
   */
  public function __construct(){
    $this->repID = $_POST['empIDS'];
    $this->dateSM = $_POST['dateSM'];
    $this->dateSY = $_POST['dateSY'];
    $this->dateEM = $_POST['dateEM'];
    $this->dateEY = $_POST['dateEY'];
  }

  /**
   *
   *
   * @param $conn
   * @return array
   */
  public function controller($conn){

    //Pulling in the employee data we will be working with
    $this->setEmpArray(init_report_employee($conn));

    //Query the contracts pertaining to the employees within the start/end bounds -> (sort by EndYear, EndMonth)
    $results = $this->gatherAccountData($conn);

    //Loop through the contracts calculating commission and appending an array to the employee
    // -> output[Employee][Commission_Array] -> Commission_Array[Month-Year][CalculatedCommission]
    $output = $this->generateOutput($results);

    //Return output to the front end
    return $output;
  }

  /**
   *
   *
   * @param $conn
   * @return array
   */
  protected function gatherAccountData($conn){
    $x = 0; // counter
    $contracts = array(); //Array that will hold the contract data

    //Construct the query
    $query = "SELECT contracts.ID, contracts.RepID, contracts.SupplierID,"
      . " contracts.UtilityID, contracts.CustomerID, contracts.StartMonth,"
      . " contracts.StartYear, contracts.EndMonth, contracts.EndYear,"
      . " contracts.AnnualMWHs, contracts.Mils, contracts.Gas_Usage,"
      . " contracts.Gas_Commission, contracts.RenewalStatusID,"
      . " utilities.Name as UtilityName, customers.Name as CustomerName,"
      . " suppliers.Name as SupplierName, reps.Last as RepLast"
      . " FROM contracts"
      . " INNER JOIN utilities ON utilities.ID = contracts.UtilityID"
      . " INNER JOIN customers ON customers.ID = contracts.CustomerID"
      . " INNER JOIN reps ON reps.ID = contracts.RepID"
      . " INNER JOIN suppliers ON suppliers.ID = contracts.SupplierID"
      . " WHERE( AnnualMWHS > 0 OR Gas_Usage > 0) "
      //TODO: Verify query and data this was translated directly from the Java version
      . " AND (( StartYear <= " . $this->getDateSY() . " AND EndYear >= " . $this->getDateSY() . " )"
      . " OR ( EndMonth = " . $this->getDateEM() . " AND EndYear =" . $this->getDateEY() . " )"
      . " OR ( EndMonth >= " . $this->getDateEM() . " AND EndYear = " . $this->getDateEY() . " )"
      . " OR ( EndMonth <= " . $this->getDateEM() . " AND EndYear = " . $this->getDateEY() . " )"
      . " OR ( StartYear > " . $this->getDateSY() . " AND EndYear < " . $this->getDateEY() . " )"
      . ") AND ( ";

    foreach($this->getRepID() as $id){
      if($x == 0){
        $query.= "contracts.RepID = " . $id . " ";
      }
      else{
        $query.= "OR contracts.RepID = " . $id . " ";
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
   *
   *
   * @param $contracts
   * @return array
   */
  //TODO: Verify logic, in comparison to production it looks like contracts are cutting out sooner than it should.
  //TODO: Profile code and look for optimizations, we are dangerously close to our 30sec limit, Java desktop app is 3 seconds max
  //TODO: Add total row to bottom of each entry
  protected function generateOutput($contracts){
    $output = array(array());
    $start = new DateTime('01-' . $this->getDateSM() . '-'. $this->getDateSY());
    $end = new DateTime('01-' . $this->getDateEM() . '-'. $this->getDateEY());
    $totalMonthly = 0;
    //Loop commission
    while($start<=$end){
      //Loop contracts
      foreach($contracts as $key => $contract){
        //Create our date variables to test contract validity
        $cStart = new DateTime('01-' . $contract->getStartMonth() . '-'. $contract->getStartYear());
        $cEnd = new DateTime('01-' . $contract->getEndMonth() . '-'. $contract->getEndYear());

        //If the contract is no longer valid unset it and move on
        if($cEnd < $start){
          unset($contracts[$key]);
        }
        //If the contract is not currently valid but will be in the future move on
        elseif($cStart > $start && $cEnd > $start){
          //Carry on nothing to see here
        }
        //Else the contract is valid and we should append the output data
        else{
          //Create a commission object
          $temp = new Commission();
          $temp->setDate($start->format('Y-m'));
          $temp->setRepID($contract->getRepId());
          $temp->setCommission(0);

          //If we already have data for this date. ex; another contract active on this Y-m
          if(isset($output[$contract->getRepName()][$temp->getDate()])){

            //If we are Electric
            if($contract->getAnnualMWHs() > 0){
              $temp->setCommission(($contract->getAnnualMWHs() * $contract->getMils())/12);
            }
            //Otherwise we are Natural Gas
            else{
              $temp->setCommission(($contract->getGasUsage() * $contract->getGasCommission())/12);
            }
            //Set the commission
            $output[$contract->getRepName()][$temp->getDate()] += $temp->getCommission();
            $output['Total'][$temp->getDate()] += $temp->getCommission();
          }

          //If we have no data for this date
          else{
            if($contract->getAnnualMWHs() > 0){
              $temp->setCommission(($contract->getAnnualMWHs() * $contract->getMils())/12);
            }
            else{
              $temp->setCommission(($contract->getGasUsage() * $contract->getGasCommission())/12);
            }
            $output[$contract->getRepName()][$temp->getDate()] = $temp->getCommission();
            $output['Total'][$temp->getDate()] += $temp->getCommission();
          }
        }
      }
      $start->add(new DateInterval(("P1M")));
    }
    //Workaround for first entry of array always being a blank entry
    if(sizeof($output[0]) == 0){
      unset($output[0]);
    }
    return $output;
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
  public function getDateSM()
  {
    return $this->dateSM;
  }

  /**
   * @param mixed $dateSM
   */
  public function setDateSM($dateSM)
  {
    $this->dateSM = $dateSM;
  }

  /**
   * @return mixed
   */
  public function getDateSY()
  {
    return $this->dateSY;
  }

  /**
   * @param mixed $dateSY
   */
  public function setDateSY($dateSY)
  {
    $this->dateSY = $dateSY;
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