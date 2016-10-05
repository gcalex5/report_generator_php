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

  public function controller($conn){

    //Pulling in the employee data we will be working with
    $this->setEmpArray(init_report_employee($conn));

    //Query the contracts pertaining to the employees within the start/end bounds -> (sort by EndYear, EndMonth)

    //Loop through the contracts calculating commission and appending an array to the employee
    // -> output[Employee][Commission_Array] -> Commission_Array[Month-Year][CalculatedCommission]

    //Return output to the front end
    return 0;
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