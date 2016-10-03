<?php
/**
 * Provides the necessary functionality to
 * generate an Electricity Renewal Report
 *
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
    $this->setEmpArray(init_report_employee($conn));

    //Pull the contracts and initialize an array of them
    $this->contracts = $this->gatherAccountData($conn);

    //Calculate the totals AnnualMWHs(% and mWh) AnnualTotals($ and %)
    $this->calculateTotals();

    //Clean up empty employees for output
    $this->empArrayCleanUp();

    $this->contractArrayTransform();
    //Return the necessary data to the front end
    return [$this->getEmpArray(), $this->getContracts()];
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
   */
  //TODO: Add a 'total' employee
  protected function calculateTotals(){
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
   * Cleanup the empty entries in the array
   */
  protected function empArrayCleanUp(){
    foreach($this->getEmpArray() as $rep){
      if($rep->getMwhTotal() == 0 && $rep->getFeeTotal() == 0){
        unset($this->empArray[$rep->getId()]);
      }
    }
  }

  protected function contractArrayTransform(){
    foreach($this->getContracts() as $contract){
      //TODO: Set The Customer Name
      //TODO: Set The Utility
      //TODO: Set The Supplier
      
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
      $contract->setRepName($this->getEmpArray()[$contract->getRepID()]->getLast()
        . ", " . $this->getEmpArray()[$contract->getRepID()]->getFirst());

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