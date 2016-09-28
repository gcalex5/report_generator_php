<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 9/23/16
 * Time: 3:04 PM
 */
class Employee implements JsonSerializable
{
  /**
   * 'reps' table
   * ID
   * First
   * Last
   * Title
   * Primary
   * Cell
   * Fax
   * Email
   * Abbr
   * Password
   * Phone
   * Title - Agent/Rep
   */

  //TODO: potentially pull and populate additional fields
  protected $id;
  protected $first;
  protected $last;
  protected $title;

  /**
   * Employee constructor.
   * @param $dbData -> Passed in database result set
   */
  public function __construct($dbData){
    $this->id = $dbData['ID'];
    $this->first = $dbData['First'];
    $this->last = $dbData['Last'];
    $this->title = $dbData['Title'];
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
      'title' => $this->getTitle()
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
}