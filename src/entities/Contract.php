<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 9/23/16
 * Time: 3:04 PM
 */

/**
 * Class Contract
 */
class Contract implements JsonSerializable{
  /**
   * 'contracts' table
   * ID
   *
   * RepID -> Query reps(ID, Fist, Last)
   *
   * SupplierID -> Query suppliers (ID, Name)
   * UtilityID -> Query utilities (ID, Name)
   * CustomerID -> Query accounts (ID, CustomerName)
   *
   * StartMonth
   * StartYear
   *
   * EndMonth
   * EndYear
   *
   * AnnualMWHs
   * Mils
   *
   * Renewal
   * RenewalStatusID
   * Gas_Usage
   * Gas_Commission
   *
   * Electric Fee = Mils * AnnualMWHs
   */
  protected $id;
  protected $repID;
  protected $supplierID;
  protected $utilityID;
  protected $customerID;
  protected $startMonth;
  protected $startYear;
  protected $endMonth;
  protected $endYear;
  protected $annualMWHs;
  protected $mils;
  protected $renewalStatusID;

  /**
   * Contract constructor.
   * @param $dbData -> Passed in database resultset
   */
  public function __construct($dbData){
    $this->id = $dbData['ID'];
    $this->repID = $dbData['RepID'];
    $this->supplierID = $dbData['SupplierID'];
    $this->utilityID = $dbData['UtilityID'];
    $this->customerID = $dbData['CustomerID'];
    $this->startMonth = $dbData['StartMonth'];
    $this->startYear = $dbData['StartYear'];
    $this->endMonth = $dbData['EndMonth'];
    $this->endYear = $dbData['EndYear'];
    $this->annualMWHs = $dbData['AnnualMWHs'];
    $this->mils = $dbData['Mils'];
    $this->renewalStatusID = $dbData['RenewalStatusID'];
  }

  /**
   * Implementation of JSON Serialize
   * @return array -> returns array allowing access to protected variables
   */
  public function jsonSerialize(){
    return [
      'id' => $this->getId(),
      'repID' => $this->getRepID(),
      'supplierID' => $this->getSupplierID(),
      'utilityID' => $this->getUtilityID(),
      'customerID' => $this->getCustomerID(),
      'startMonth' => $this->getStartMonth(),
      'startYear' => $this->getStartYear(),
      'endMonth' => $this->getEndMonth(),
      'endYear' => $this->getEndYear(),
      'annualMWHs' => $this->getAnnualMWHs(),
      'mils' => $this->getMils(),
      'renewalStatusID' => $this->getRenewalStatusID(),
    ];
  }

  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param mixed $id
   */
  public function setId($id)
  {
    $this->id = $id;
  }

  /**
   * @return mixed
   */
  public function getRepID()
  {
    return $this->repID;
  }

  /**
   * @param mixed $repID
   */
  public function setRepID($repID)
  {
    $this->repID = $repID;
  }

  /**
   * @return mixed
   */
  public function getSupplierID()
  {
    return $this->supplierID;
  }

  /**
   * @param mixed $supplierID
   */
  public function setSupplierID($supplierID)
  {
    $this->supplierID = $supplierID;
  }

  /**
   * @return mixed
   */
  public function getUtilityID()
  {
    return $this->utilityID;
  }

  /**
   * @param mixed $utilityID
   */
  public function setUtilityID($utilityID)
  {
    $this->utilityID = $utilityID;
  }

  /**
   * @return mixed
   */
  public function getCustomerID()
  {
    return $this->customerID;
  }

  /**
   * @param mixed $customerID
   */
  public function setCustomerID($customerID)
  {
    $this->customerID = $customerID;
  }

  /**
   * @return mixed
   */
  public function getStartMonth()
  {
    return $this->startMonth;
  }

  /**
   * @param mixed $startMonth
   */
  public function setStartMonth($startMonth)
  {
    $this->startMonth = $startMonth;
  }

  /**
   * @return mixed
   */
  public function getStartYear()
  {
    return $this->startYear;
  }

  /**
   * @param mixed $startYear
   */
  public function setStartYear($startYear)
  {
    $this->startYear = $startYear;
  }

  /**
   * @return mixed
   */
  public function getEndMonth()
  {
    return $this->endMonth;
  }

  /**
   * @param mixed $endMonth
   */
  public function setEndMonth($endMonth)
  {
    $this->endMonth = $endMonth;
  }

  /**
   * @return mixed
   */
  public function getEndYear()
  {
    return $this->endYear;
  }

  /**
   * @param mixed $endYear
   */
  public function setEndYear($endYear)
  {
    $this->endYear = $endYear;
  }

  /**
   * @return mixed
   */
  public function getAnnualMWHs()
  {
    return $this->annualMWHs;
  }

  /**
   * @param mixed $annualMWHs
   */
  public function setAnnualMWHs($annualMWHs)
  {
    $this->annualMWHs = $annualMWHs;
  }

  /**
   * @return mixed
   */
  public function getMils()
  {
    return $this->mils;
  }

  /**
   * @param mixed $mils
   */
  public function setMils($mils)
  {
    $this->mils = $mils;
  }

  /**
   * @return mixed
   */
  public function getRenewalStatusID()
  {
    return $this->renewalStatusID;
  }

  /**
   * @param mixed $renewalStatusID
   */
  public function setRenewalStatusID($renewalStatusID)
  {
    $this->renewalStatusID = $renewalStatusID;
  }
}