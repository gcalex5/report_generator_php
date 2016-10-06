<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 10/6/16
 * Time: 4:26 PM
 */

class ComboReport{

  public function __construct(){
  }

  /**
   * Calls all 4 functions with the appropriate data in $_POST
   *
   * @param $conn -> passed in mysqli connection
   * @return array
   */
  public function controller($conn){
    //Manipulate $_POST data and run electric
    $_POST['dateM'] = $_POST['dateCEM'];
    $_POST['dateY'] = $_POST['dateCEY'];
    $renewal = new RenewalSummary();
    $E = $renewal->controller($conn, 'electric');

    //Manipulate $_POST data and run gas
    $_POST['dateM'] = $_POST['dateCGM'];
    $_POST['dateY'] = $_POST['dateCGY'];
    $G = $renewal->controller($conn, 'gas');

    //Manipulate $_POST data and run book
    $_POST['dateM'] = $_POST['dateCBM'];
    $_POST['dateY'] = $_POST['dateCBY'];
    $book = new BookOfBusiness();
    $B = $book->controller($conn);

    //Manipulate $_POST data and run Commission
    //TODO: remove this after writing display code until it is more efficient
    $_POST['dateSM'] = $_POST['dateCCSM'];
    $_POST['dateSY'] = $_POST['dateCCSY'];
    $_POST['dateEM'] = $_POST['dateCCEM'];
    $_POST['dateEY'] = $_POST['dateCCEY'];
    $commission = new MonthlyCommission();
    $C = $commission->controller($conn);

    //Return output arrays
    return [$E, $G, $B, $C];
  }
}