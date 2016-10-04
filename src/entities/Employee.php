<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 9/23/16
 * Time: 3:04 PM
 */
class Employee implements JsonSerializable
{
  protected $id;
  protected $first;
  protected $last;
  protected $title;

  /** Electricity Variables **/
  protected $mwhRenewed;
  protected $mwhWorking;
  protected $mwhBack;
  protected $mwhLost;
  protected $mwhTotal;
  protected $feeRenewed;
  protected $feeWorking;
  protected $feeBack;
  protected $feeLost;
  protected $feeTotal;

  /** Gas Variables **/
  protected $gasRenewed;
  protected $gasWorking;
  protected $gasBack;
  protected $gasLost;
  protected $gasTotal;
  protected $gasFeeRenewed;
  protected $gasFeeWorking;
  protected $gasFeeBack;
  protected $gasFeeLost;
  protected $gasFeeTotal;

  /**
   * Employee constructor.
   * @param $dbData -> Passed in database result set
   */
  public function __construct($dbData){
    $this->id = $dbData['ID'];
    $this->first = $dbData['First'];
    $this->last = $dbData['Last'];
    $this->title = $dbData['Title'];
    
    /** Electricity Variables **/
    $this->mwhRenewed = 0;
    $this->mwhWorking = 0;
    $this->mwhBack = 0;
    $this->mwhLost = 0;
    $this->mwhTotal = 0;
    $this->feeRenewed = 0;
    $this->feeWorking = 0;
    $this->feeBack = 0;
    $this->feeLost = 0;
    $this->feeTotal = 0;
    
    /** Gas Variables **/
    $this->gasRenewed = 0;
    $this->gasWorking = 0;
    $this->gasBack = 0;
    $this->gasLost = 0;
    $this->gasTotal = 0;
    $this->gasFeeRenewed = 0;
    $this->gasFeeWorking = 0;
    $this->gasFeeBack = 0;
    $this->gasFeeLost = 0;
    $this->gasFeeTotal = 0;
  }

  /**
   * Implementation of jsonSerialize
   * @return array -> return array allowing access to protected variables
   */
  public function jsonSerialize() {
    return [
      'id' => $this->getId(),
      'first' => $this->getFirst(),
      'last' => $this->getLast(),
      'title' => $this->getTitle(),

      /** Electricity Variables **/
      'mwhRenewed' => $this->getMwhRenewed(),
      'mwhWorking' => $this->getMwhWorking(),
      'mwhBack' => $this->getMwhBack(),
      'mwhLost' => $this->getMwhLost(),
      'mwhTotal' => $this->getMwhTotal(),
      'feeRenewed' => $this->getFeeRenewed(),
      'feeWorking' => $this->getFeeWorking(),
      'feeBack' => $this->getFeeBack(),
      'feeLost' => $this->getFeeLost(),
      'feeTotal' => $this->getFeeTotal(),

      /** Gas Variables **/
      'gasRenewed' => $this->getGasRenewed(),
      'gasWorking' => $this->getGasWorking(),
      'gasBack' => $this->getGasBack(),
      'gasLost' => $this->getGasLost(),
      'gasTotal' => $this->getGasTotal(),
      'gasFeeRenewed' => $this->getGasFeeRenewed(),
      'gasFeeWorking' => $this->getGasFeeWorking(),
      'gasFeeBack' => $this->getGasFeeBack(),
      'gasFeeLost' => $this->getGasFeeLost(),
      'gasFeeTotal' => $this->getGasFeeTotal()
    ];
  }

  //Getter/Setter functions
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
  public function getFirst()
  {
    return $this->first;
  }

  /**
   * @param mixed $first
   */
  public function setFirst($first)
  {
    $this->first = $first;
  }

  /**
   * @return mixed
   */
  public function getLast()
  {
    return $this->last;
  }

  /**
   * @param mixed $last
   */
  public function setLast($last)
  {
    $this->last = $last;
  }

  /**
   * @return mixed
   */
  public function getMwhTotal()
  {
    return $this->mwhTotal;
  }

  /**
   * @param mixed $mwhTotal
   */
  public function setMwhTotal($mwhTotal)
  {
    $this->mwhTotal = $mwhTotal;
  }

  /**
   * @return mixed
   */
  public function getFeeTotal()
  {
    return $this->feeTotal;
  }

  /**
   * @param mixed $feeTotal
   */
  public function setFeeTotal($feeTotal)
  {
    $this->feeTotal = $feeTotal;
  }

  /**
   * @return mixed
   */
  public function getTitle()
  {
    return $this->title;
  }

  /**
   * @param mixed $title
   */
  public function setTitle($title)
  {
    $this->title = $title;
  }

  /**
   * @return mixed
   */
  public function getMwhRenewed()
  {
    return $this->mwhRenewed;
  }

  /**
   * @param mixed $mwhRenewed
   */
  public function setMwhRenewed($mwhRenewed)
  {
    $this->mwhRenewed = $mwhRenewed;
  }

  /**
   * @return mixed
   */
  public function getMwhWorking()
  {
    return $this->mwhWorking;
  }

  /**
   * @param mixed $mwhWorking
   */
  public function setMwhWorking($mwhWorking)
  {
    $this->mwhWorking = $mwhWorking;
  }

  /**
   * @return mixed
   */
  public function getMwhBack()
  {
    return $this->mwhBack;
  }

  /**
   * @param mixed $mwhBack
   */
  public function setMwhBack($mwhBack)
  {
    $this->mwhBack = $mwhBack;
  }

  /**
   * @return mixed
   */
  public function getMwhLost()
  {
    return $this->mwhLost;
  }

  /**
   * @param mixed $mwhLost
   */
  public function setMwhLost($mwhLost)
  {
    $this->mwhLost = $mwhLost;
  }

  /**
   * @return mixed
   */
  public function getFeeRenewed()
  {
    return $this->feeRenewed;
  }

  /**
   * @param mixed $feeRenewed
   */
  public function setFeeRenewed($feeRenewed)
  {
    $this->feeRenewed = $feeRenewed;
  }

  /**
   * @return mixed
   */
  public function getFeeWorking()
  {
    return $this->feeWorking;
  }

  /**
   * @param mixed $feeWorking
   */
  public function setFeeWorking($feeWorking)
  {
    $this->feeWorking = $feeWorking;
  }

  /**
   * @return mixed
   */
  public function getFeeBack()
  {
    return $this->feeBack;
  }

  /**
   * @param mixed $feeBack
   */
  public function setFeeBack($feeBack)
  {
    $this->feeBack = $feeBack;
  }

  /**
   * @return mixed
   */
  public function getFeeLost()
  {
    return $this->feeLost;
  }

  /**
   * @param mixed $feeLost
   */
  public function setFeeLost($feeLost)
  {
    $this->feeLost = $feeLost;
  }

  /**
   * @return mixed
   */
  public function getGasRenewed()
  {
    return $this->gasRenewed;
  }

  /**
   * @param mixed $gasRenewed
   */
  public function setGasRenewed($gasRenewed)
  {
    $this->gasRenewed = $gasRenewed;
  }

  /**
   * @return int
   */
  public function getGasWorking()
  {
    return $this->gasWorking;
  }

  /**
   * @param int $gasWorking
   */
  public function setGasWorking($gasWorking)
  {
    $this->gasWorking = $gasWorking;
  }

  /**
   * @return int
   */
  public function getGasBack()
  {
    return $this->gasBack;
  }

  /**
   * @param int $gasBack
   */
  public function setGasBack($gasBack)
  {
    $this->gasBack = $gasBack;
  }

  /**
   * @return int
   */
  public function getGasLost()
  {
    return $this->gasLost;
  }

  /**
   * @param int $gasLost
   */
  public function setGasLost($gasLost)
  {
    $this->gasLost = $gasLost;
  }

  /**
   * @return int
   */
  public function getGasTotal()
  {
    return $this->gasTotal;
  }

  /**
   * @param int $gasTotal
   */
  public function setGasTotal($gasTotal)
  {
    $this->gasTotal = $gasTotal;
  }

  /**
   * @return int
   */
  public function getGasFeeRenewed()
  {
    return $this->gasFeeRenewed;
  }

  /**
   * @param int $gasFeeRenewed
   */
  public function setGasFeeRenewed($gasFeeRenewed)
  {
    $this->gasFeeRenewed = $gasFeeRenewed;
  }

  /**
   * @return int
   */
  public function getGasFeeWorking()
  {
    return $this->gasFeeWorking;
  }

  /**
   * @param int $gasFeeWorking
   */
  public function setGasFeeWorking($gasFeeWorking)
  {
    $this->gasFeeWorking = $gasFeeWorking;
  }

  /**
   * @return int
   */
  public function getGasFeeBack()
  {
    return $this->gasFeeBack;
  }

  /**
   * @param int $gasFeeBack
   */
  public function setGasFeeBack($gasFeeBack)
  {
    $this->gasFeeBack = $gasFeeBack;
  }

  /**
   * @return int
   */
  public function getGasFeeLost()
  {
    return $this->gasFeeLost;
  }

  /**
   * @param int $gasFeeLost
   */
  public function setGasFeeLost($gasFeeLost)
  {
    $this->gasFeeLost = $gasFeeLost;
  }

  /**
   * @return int
   */
  public function getGasFeeTotal()
  {
    return $this->gasFeeTotal;
  }

  /**
   * @param int $gasFeeTotal
   */
  public function setGasFeeTotal($gasFeeTotal)
  {
    $this->gasFeeTotal = $gasFeeTotal;
  }
}