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
  protected $id;
  protected $repID;
  protected $supplierID;
  protected $supplierName;
  protected $utilityID;
  protected $utilityName;
  protected $customerID;
  protected $customerName;
  protected $startMonth;
  protected $startYear;
  protected $endMonth;
  protected $endYear;
  protected $annualMWHs;
  protected $mils;
  protected $gasUsage;
  protected $gasCommission;
  protected $renewalStatusID;
  protected $renewalStatus;
  protected $repName;

  /**
   * Contract constructor.
   * @param $dbData -> Passed in database resultset
   */
  public function __construct($dbData){
    $this->id = $dbData['ID'];
    $this->repID = $dbData['RepID'];
    $this->supplierID = $dbData['SupplierID'];
    $this->supplierName = $dbData['SupplierName'];
    $this->utilityID = $dbData['UtilityID'];
    $this->utilityName = $dbData['UtilityName'];
    $this->customerID = $dbData['CustomerID'];
    $this->customerName = $dbData['CustomerName'];
    $this->startMonth = $dbData['StartMonth'];
    $this->startYear = $dbData['StartYear'];
    $this->endMonth = $dbData['EndMonth'];
    $this->endYear = $dbData['EndYear'];
    $this->annualMWHs = $dbData['AnnualMWHs'];
    $this->mils = $dbData['Mils'];
    $this->gasUsage = $dbData['Gas_Usage'];
    $this->gasCommission = $dbData['Gas_Commission'];
    $this->renewalStatusID = $dbData['RenewalStatusID'];
    $this->repName = $dbData['RepLast'];
  }

  /**
   * Implementation of jsonSerialize
   * @return array -> return array allowing access to protected variables
   */
  public function jsonSerialize(){
    return [
      'id' => $this->getId(),
      'repID' => $this->getRepID(),
      'supplierID' => $this->getSupplierID(),
      'supplierName' => $this->getSupplierName(),
      'utilityID' => $this->getUtilityID(),
      'utilityName' => $this->getUtilityName(),
      'customerID' => $this->getCustomerID(),
      'customerName' => $this->getCustomerName(),
      'startMonth' => $this->getStartMonth(),
      'startYear' => $this->getStartYear(),
      'endMonth' => $this->getEndMonth(),
      'endYear' => $this->getEndYear(),
      'annualMWHs' => $this->getAnnualMWHs(),
      'mils' => $this->getMils(),
      'gasUsage' => $this->getGasUsage(),
      'gasCommission' => $this->getGasCommission(),
      'renewalStatusID' => $this->getRenewalStatusID(),
      'renewalStatus' => $this->getRenewalStatus(),
      'repName' => $this->getRepName()
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

  /**
   * @return mixed
   */
  public function getRenewalStatus()
  {
    return $this->renewalStatus;
  }

  /**
   * @param mixed $renewalStatus
   */
  public function setRenewalStatus($renewalStatus)
  {
    $this->renewalStatus = $renewalStatus;
  }

  /**
   * @return mixed
   */
  public function getRepName()
  {
    return $this->repName;
  }

  /**
   * @param mixed $repName
   */
  public function setRepName($repName)
  {
    $this->repName = $repName;
  }

  /**
   * @return mixed
   */
  public function getGasUsage()
  {
    return $this->gasUsage;
  }

  /**
   * @param mixed $gasUsage
   */
  public function setGasUsage($gasUsage)
  {
    $this->gasUsage = $gasUsage;
  }

  /**
   * @return mixed
   */
  public function getGasCommission()
  {
    return $this->gasCommission;
  }

  /**
   * @param mixed $gasCommission
   */
  public function setGasCommission($gasCommission)
  {
    $this->gasCommission = $gasCommission;
  }

  /**
   * @return mixed
   */
  public function getUtilityName()
  {
    return $this->utilityName;
  }

  /**
   * @param mixed $utilityName
   */
  public function setUtilityName($utilityName)
  {
    $this->utilityName = $utilityName;
  }

  /**
   * @return mixed
   */
  public function getCustomerName()
  {
    return $this->customerName;
  }

  /**
   * @param mixed $customerName
   */
  public function setCustomerName($customerName)
  {
    $this->customerName = $customerName;
  }

  /**
   * @return mixed
   */
  public function getSupplierName()
  {
    return $this->supplierName;
  }

  /**
   * @param mixed $supplierName
   */
  public function setSupplierName($supplierName)
  {
    $this->supplierName = $supplierName;
  }
}