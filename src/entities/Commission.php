<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 10/6/16
 * Time: 12:36 PM
 */

class Commission{
  protected $repID;
  protected $date;
  protected $commission;

  public function __construct(){

  }

  public function jsonSerialize(){
    return [
      'repID' => $this->getRepID(),
      'date' => $this->getDate(),
      'commission' => $this->getCommission()
    ];
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
  public function getDate()
  {
    return $this->date;
  }

  /**
   * @param mixed $date
   */
  public function setDate($date)
  {
    $this->date = $date;
  }

  /**
   * @return mixed
   */
  public function getCommission()
  {
    return $this->commission;
  }

  /**
   * @param mixed $commission
   */
  public function setCommission($commission)
  {
    $this->commission = $commission;
  }
}