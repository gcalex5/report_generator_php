<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 10/5/16
 * Time: 11:59 AM
 */

/**
 * Class Utility
 */
class Utility implements JsonSerializable{

  protected $name;
  protected $contracts;
  protected $mwh;
  protected $mcf;
  protected $annualFeeE;
  protected $annualFeeG;
  protected $totalAnnualFee;

  /**
   * Utility constructor.
   */
  public function __construct(){
    $this->name = '';
    $this->contracts = 0;
    $this->mwh = 0;
    $this->mcf = 0;
    $this->annualFeeE = 0;
    $this->annualFeeG = 0;
    $this->totalAnnualFee = 0;
  }

  /**
   * Implementation of jsonSerialize
   * @return array -> return array allowing access to protected variables
   */
  public function jsonSerialize() {
    return [
      'name' => $this->getName(),
      'contracts' => $this->getContracts(),
      'mwh' => $this->getMwh(),
      'mcf' => $this->getMcf(),
      'annualFeeE' => $this->getAnnualFeeE(),
      'annualFeeG' => $this->getAnnualFeeG(),
      'totalAnnualFee' => $this->getTotalAnnualFee()
    ];
  }

  /**
   * @return mixed
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * @param mixed $name
   */
  public function setName($name)
  {
    $this->name = $name;
  }

  /**
   * @return mixed
   */
  public function getContracts()
  {
    return $this->contracts;
  }

  /**
   * @param mixed $contracts
   */
  public function setContracts($contracts)
  {
    $this->contracts = $contracts;
  }

  /**
   * @return mixed
   */
  public function getMwh()
  {
    return $this->mwh;
  }

  /**
   * @param mixed $mwh
   */
  public function setMwh($mwh)
  {
    $this->mwh = $mwh;
  }

  /**
   * @return mixed
   */
  public function getMcf()
  {
    return $this->mcf;
  }

  /**
   * @param mixed $mcf
   */
  public function setMcf($mcf)
  {
    $this->mcf = $mcf;
  }

  /**
   * @return mixed
   */
  public function getAnnualFeeE()
  {
    return $this->annualFeeE;
  }

  /**
   * @param mixed $annualFeeE
   */
  public function setAnnualFeeE($annualFeeE)
  {
    $this->annualFeeE = $annualFeeE;
  }

  /**
   * @return mixed
   */
  public function getAnnualFeeG()
  {
    return $this->annualFeeG;
  }

  /**
   * @param mixed $annualFeeG
   */
  public function setAnnualFeeG($annualFeeG)
  {
    $this->annualFeeG = $annualFeeG;
  }

  /**
   * @return mixed
   */
  public function getTotalAnnualFee()
  {
    return $this->totalAnnualFee;
  }

  /**
   * @param mixed $totalAnnualFee
   */
  public function setTotalAnnualFee($totalAnnualFee)
  {
    $this->totalAnnualFee = $totalAnnualFee;
  }
}
