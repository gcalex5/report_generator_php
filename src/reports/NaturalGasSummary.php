<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 9/26/16
 * Time: 12:08 PM
 */

//TODO: Merge Electricity/Natural Gas Summary once functional into one generic class
/**
 * Class NaturalGasSummary
 */
class NaturalGasSummary{
  protected $repID = array();
  protected $datEM;
  protected $dateEY;
  protected $empArray = array();
  protected $contracts = array();


  /**
   * Construct an instance of the object; populating the variables
   * necessary to generate an Natural Gas Renewal Report
   *
   * NaturalGasSummary constructor.
   */
  public function __construct(){
    $this->repID = $_POST['empIDS'];
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
    $this->setEmpArray(init_report_employee($conn));
    $this->setContracts($this->gatherAccountData($conn));

    return[array(), array()];

  }

  protected function gatherAccountData($conn){
    $x = 0;
    $contracts = array();
    $query = "SELECT * FROM contracts WHERE( Gas_Usage > 0 AND EndMonth = "
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


    return $foo;
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
  public function getDatEM()
  {
    return $this->datEM;
  }

  /**
   * @param mixed $datEM
   */
  public function setDatEM($datEM)
  {
    $this->datEM = $datEM;
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