<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 9/30/16
 * Time: 12:21 PM
 */

/**
include 'src/tools/queries.php';
include 'src/reports/ElectricitySummary.php';
include 'src/entities/Employee.php';
include 'src/entities/Contract.php';
require_once 'lib/composer/vendor/twig/twig/lib/Twig/Autoloader.php';
**/

/**
 * Class ReportController
 */
class ReportController{
  public $template;
  public $twig;
  public $conn;

  /**
   * ReportController constructor.
   */
  public function __construct(){
    $this->init_twig();
    $this->init_db_connection();
    if(!isset($_POST['partial'])){
      $this->init_menu();
    }
  }

  /**
   *
   */
  function init_twig(){
    Twig_Autoloader::register();
    $loader = new Twig_Loader_Filesystem('templates');
    $this->setTwig(new Twig_Environment($loader, array(
      'cache' => false,
      'debug' => true,
    )));
    $this->getTwig()->addExtension(new Twig_Extension_Debug());
    $this->setTemplate($this->getTwig()->loadTemplate('index.html.twig'));
  }

  /**
   *
   */
  function init_db_connection(){
    //Database connection
    $this->setConn(init_database());
    if (!mysqli_ping($this->getConn())) {
      //TODO: check connection and re-attempt then display error
      echo('Failed Database connection');
      exit;
    }
  }

  /**
   *
   */
  public function init_menu(){
    $reps = query_reps($this->getConn());
    $_POST['loaded']['reps'] = true;

    $dates = query_date_ranges($this->getConn());
    $_POST['loaded']['dates'] = true;
    echo $this->getTemplate()->render(array('reps' => $reps, 'dates' => $dates));
  }

  /**
   *
   */
  public function report_request(){
    if(isset($_POST['report']) && !empty($_POST['report'])) {
      if($_POST['report'] == 'electric') {
        $this->electric();
      }
    }
  }

  /**
   *
   */
  public function electric(){
    $report = new ElectricitySummary();
    $output = $report->controller($this->getConn());
    $this->setTemplate($this->getTwig()->loadTemplate('content.html.twig'));
    echo $this->getTemplate()->render(array('output'  => $output));
  }

  /**
   * @return mixed
   */
  public function getTemplate()
  {
    return $this->template;
  }

  /**
   * @param mixed $template
   */
  public function setTemplate($template)
  {
    $this->template = $template;
  }

  /**
   * @return mixed
   */
  public function getTwig()
  {
    return $this->twig;
  }

  /**
   * @param mixed $twig
   */
  public function setTwig($twig)
  {
    $this->twig = $twig;
  }

  /**
   * @return mixed
   */
  public function getConn()
  {
    return $this->conn;
  }

  /**
   * @param mixed $conn
   */
  public function setConn($conn)
  {
    $this->conn = $conn;
  }
}