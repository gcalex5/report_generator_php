<?php
/**
 * 'RenewalSummary.php' contains the necessary logic to generate the data
 * required to create a Renewal Summary. This is toggled between either Electric
 * or Natural Gas based on user input. It will display both numeric and
 * percentage based output as well as a list of contracts that are relevant to
 * the time period queried. 
 *
 * User: alex
 * Date: 9/26/16
 * Time: 12:08 PM
 */

/**
 * Class RenewalSummary
 */
class RenewalSummary{
  protected $repID = array();
  protected $dateM;
  protected $dateY;
  protected $empArray = array();
  protected $contracts = array();

  /**
   * RenewalSummary constructor.
   *
   * Construct the initial object and populate the variables necessary
   * to generate an Electricity Renewal Report
   *
   * Shows all active Contracts ending at the time sorted by Rep/Agent
   */
  public function __construct(){
    $this->setRepID($_POST['empIDS']);
    $this->setDateM($_POST['dateM']);
    $this->setDateY($_POST['dateY']);
  }

  /**
   * Controller function for Renewal Reports
   * Handles calling the necessary functions to generate
   * the output of renewal reports
   *
   * @param $conn -> Passed in mysqli connection
   * @param $type -> Passed in string denoting the type of report
   * @return array -> Return an array containing the output Keys: [0]Employee data [1]Contract data
   */
  public function controller($conn, $type){
    //Initialize an array of employees
    $this->setEmpArray(init_report_employee($conn));

    //Pull the contracts and initialize an array of them
    $this->contracts = $this->gatherAccountData($conn, $type);

    if($type == 'electric'){
      //Calculate the totals AnnualMWHs(% and mWh) AnnualTotals($ and %)
      $this->calculateTotalsE();
    }
    else{
      $this->calculateTotalsG();
    }

    //Clean up empty employees for output
    $this->empArrayCleanUp($type);

    $this->contractArrayTransform();
    //Return the necessary data to the front end
    return [$this->getEmpArray(), $this->getContracts()];
  }

  /**
   * Gather the account data and parse it into an array of contract objects
   *
   * @param $conn -> Passed in mysqli connection
   * @param $type -> Passed in conditional denoting which report we want
   * @return array -> return array of contract objects
   */
  protected function gatherAccountData($conn, $type){
    $x = 0;
    $contracts = array();
    //Electric Query
    $query = "SELECT contracts.ID, contracts.RepID, contracts.SupplierID,"
      . " contracts.UtilityID, contracts.CustomerID, contracts.StartMonth,"
      . " contracts.StartYear, contracts.EndMonth, contracts.EndYear,"
      . " contracts.AnnualMWHs, contracts.Mils, contracts.Gas_Usage,"
      . " contracts.Gas_Commission, contracts.RenewalStatusID,"
      . " utilities.Name as UtilityName, customers.Name as CustomerName,"
      . " suppliers.Name as SupplierName FROM contracts"
      . " INNER JOIN utilities ON utilities.ID = contracts.UtilityID"
      . " INNER JOIN customers ON customers.ID = contracts.CustomerID"
      . " INNER JOIN reps ON reps.ID = contracts.RepID"
      . " INNER JOIN suppliers on suppliers.ID = contracts.SupplierID";
    //Electric Query
    if($type == 'electric'){
      $query .= " WHERE( AnnualMWHS > 0";
    }
    //Natural Gas Query
    else{
      $query .= " WHERE( Gas_Usage > 0";
    }
    //Append on the date barriers
    $query .= " AND EndMonth = " . $this->getDateM() . " AND EndYear ="
      . $this->getDateY() . ") AND ( ";

    //Add the conditionals for the representatives
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
   * Calculate the totals and set them to the Employee array
   * Percentages/Fee/mWh's
   * Totals: Overall total, Renewed, Working, Back, and Lost
   */
  //TODO: Add a 'total' employee
  //TODO: Merge Electric/Gas into one function
  protected function calculateTotalsE(){
    foreach($this->getContracts() as $contract){
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
   * Calculate the totals and set them to the Employee array
   * Percentages/Fee/Usage
   * Totals: Overall total, Renewed, Working, Back, and Lost
   */
  //TODO: Add a 'total' employee
  protected function calculateTotalsG(){
    foreach($this->getContracts() as $contract){
      //Grab the Employee and set it to rep
      $rep = $this->getEmpArray()[$contract->getRepID()];
      //Calculate the totals on the contract
      if(isset($rep)){
        $rep->setGasTotal($rep->getGasTotal() + $contract->getGasUsage());
        $rep->setGasFeeTotal($rep->getGasFeeTotal() +
          ($contract->getGasUsage() * $contract->getGasCommission()));

        //Set and append appropriate totals
        //Total Fee = Fee + (Commission * Annual Usage)
        if(isset($contract)){
          if($contract->getRenewalStatusID() == 8){
            $rep->setGasFeeRenewed($rep->getFeeRenewed() +
              ($contract->getGasUsage() * $contract->getGasCommission()));
            $rep->setGasRenewed($rep->getGasRenewed() + $contract->getGasUsage());
          }
          elseif($contract->getRenewalStatusID() == 9
            || $contract->getRenewalStatusID() == 11){
            $rep->setGasFeeWorking($rep->getFeeWorking() +
              ($contract->getGasUsage() + $contract->getGasCommission()));
            $rep->setGasWorking($rep->getGasWorking() + $contract->getGasUsage());
          }
          elseif($contract->getRenewalStatusID() == 1){
            $rep->setGasFeeBack($rep->getFeeBack() +
              ($contract->getGasUsage() + $contract->getGasCommission()));
            $rep->setGasBack($rep->getGasBack() + $contract->getGasUsage());
          }
          else{
            $rep->setGasFeeLost($rep->getFeeLost() +
              ($contract->getGasUsage() + $contract->getGasCommission()));
            $rep->setGasLost($rep->getGasLost() + $contract->getGasUsage());
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
   * Cleanup the empty entries in the array
   *
   * @param $type -> Passed in flag denoting electric or gas
   */
  protected function empArrayCleanUp($type){
    foreach($this->getEmpArray() as $rep){
      if($type == 'electric'){
        if($rep->getMwhTotal() == 0 && $rep->getFeeTotal() == 0){
          unset($this->empArray[$rep->getId()]);
        }
      }
      else{
       if($rep->getGasTotal() == 0 && $rep->getGasFeeTotal() == 0){
         unset($this->empArray[$rep->getId()]);
       }
      }
    }
  }

  protected function contractArrayTransform(){
    foreach($this->getContracts() as $contract){
      //Renewal Status 8=Renewed 9&11=Working 1=Back Else=Lost
      if($contract->getRenewalStatusID() == 8){
        $contract->setRenewalStatus('Renewed');
      }
      elseif($contract->getRenewalStatusID() == 9 ||
        $contract->getRenewalStatusID()==11){
        $contract->setRenewalStatus('Working');
      }
      elseif($contract->getRenewalStatusID() == 1){
        $contract->setRenewalStatus('Back-To-Utility');
      }
      else{
        $contract->setRenewalStatus('Lost');
      }

      //Append the Last, First onto the contract object
      if(isset($this->getEmpArray()[$contract->getRepID()])){
        $contract->setRepName($this->getEmpArray()[$contract->getRepID()]->getLast()
          . ", " . $this->getEmpArray()[$contract->getRepID()]->getFirst());
      }
      else{
        $contract->setRepName('Unknown, Unknown');
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
  public function getDateM()
  {
    return $this->dateM;
  }

  /**
   * @param mixed $dateM
   */
  public function setDateM($dateM)
  {
    $this->dateM = $dateM;
  }

  /**
   * @return mixed
   */
  public function getDateY()
  {
    return $this->dateY;
  }

  /**
   * @param mixed $dateY
   */
  public function setDateY($dateY)
  {
    $this->dateY = $dateY;
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