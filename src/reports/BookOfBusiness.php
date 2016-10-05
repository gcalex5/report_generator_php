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
  protected $dateM;
  protected $dateY;
  protected $empArray = array();
  protected $contracts = array();

  /**
   * BookOfBusiness constructor.
   */
  public function __construct(){
    $this->setRepID($_POST['empIDS']);
    $this->setDateM($_POST['dateM']);
    $this->setDateY($_POST['dateY']);
  }

  public function controller($conn){
    $this->setEmpArray(init_report_employee($conn));

    $results = $this->gatherAccountData($conn);

    $output = $this->generateOutput($results);

    return array($output[0], $output[1]);
  }

  /**
   * Gather the account data and parse it into an array of contract objects
   *
   * @param $conn -> Passed in mysqli connection
   * @return array -> return array of contract objects
   */
  //TODO: Migrate this over to 'queries.php'
  //TODO: Ensure all date bounds are accounted for
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
      . " AND ( EndMonth = " . $this->getDateM() . " AND EndYear =". $this->getDateY()
      . " OR ( StartYear <= ". $this->getDateY() . " AND EndYear > " . $this->getDateY() . ")";

    if($this->getDateM() != 12){
      $query .= " OR ( StartYear<= " . $this->getDateY() . " AND EndMonth = " . ($this->getDateM() + 1)
        . " AND EndYear = " . $this->getDateY() . ". )) AND (";
    }
    else{
      $query .= " OR ( StartYear<= " . $this->getDateY() . " AND EndMonth = 1"
        . " AND EndYear = " . ($this->getDateY() + 1) . " )) AND (";
    }
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

  protected function generateOutput($contracts){
    $outputTop = array(array());
    $outputBottom = array(array());

    //Loop contracts
    foreach($contracts as $contract){
      //Assign contract to a rep
      $outputTop[$contract->getRepID()][] = $contract;

      //If this employee does not have a record of the utility
      //Instantiate it and append the totals
      if(!isset($outputBottom[$contract->getRepID()][$contract->getUtilityName()])){
        $util = new Utility();
        $util->setName($contract->getUtilityName());
        //Electric
        $util->setMwh($contract->getAnnualMWHs());
        $util->setAnnualFeeE($contract->getAnnualMWHs() * $contract->getMils());
        //Gas
        $util->setMcf($contract->getGasUsage());
        $util->setAnnualFeeG($contract->getGasUsage() * $contract->getGasCommission());
        //Total
        $util->setContracts(1);
        $util->setTotalAnnualFee($util->getAnnualFeeE() + $util->getAnnualFeeG());
        $outputBottom[$contract->getRepID()][$contract->getUtilityID()] = $util;
      }
      //If this employee does have a record of the utility
      //Append the totals
      else{
        $util = $outputBottom[$contract->getRepID()][$contract->getUtilityName()];
        //Electric
        $util->setMwh($util->getMwh() + $contract->getAnnualMWHs());
        $util->setAnnualFeeE($util->getAnnualFeeE +
          ($contract->getAnnualMWHs() * $contract->getMils()));
        //Gas
        $util->setMcf($util->getMcf() + $contract->getGasUsage());
        $util->setAnnualFeeG($util->getAnnualFeeG() +
          ($contract->getGasUsage() * $contract->getGasCommission()));
        //Total
        $util->setContracts($util->getContracts() + 1);
        $util->setTotalAnnualFee($util->getTotalAnnualFee() +
          ($util->getAnnualFeeE() + $util->getAnnualFeeG()));
        $outputBottom[$contract->getRepID()][$contract->getUtilityName()] = $util;
      }
    }
    //TODO: Add a total entity for both top/bottom output

    //Workaround for first entry of array always being a blank entry
    if(sizeof($outputTop[0]) == 0){
      unset($outputTop[0]);
    }
    if(sizeof($outputBottom[0] == 0)){
      unset($outputBottom[0]);
    }
    return [$outputTop, $outputBottom];
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