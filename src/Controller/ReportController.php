<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 9/30/16
 * Time: 12:21 PM
 */

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
   * Initialize Twig and load up the index template
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
   * Initialize the connection to the database
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
   * Initialize the menu
   */
  public function init_menu(){
    $reps = query_reps($this->getConn());
    $dates = query_date_ranges($this->getConn());
    echo $this->getTemplate()->render(array('reps' => $reps, 'dates' => $dates));
  }

  /**
   * Accept the POST request and fire off the correct function to
   * start the construction of a report
   */
  public function report_request(){
    if(isset($_POST['report']) && !empty($_POST['report'])) {
      if($_POST['report'] == 'electric') {
        $this->electric();
      }
      if($_POST['report'] == 'gas'){
        $this->gas();
      }
      if($_POST['report'] == 'book'){
        $this->book();
      }
      if($_POST['report'] == 'commission'){
        $this->commission();
      }
    }
  }

  /**
   * Handles generating an Electric Renewal Report
   */
  public function electric(){
    $report = new RenewalSummary();
    $output = $report->controller($this->getConn(), 'electric');
    $this->setTemplate($this->getTwig()->loadTemplate('content.html.twig'));
    echo $this->getTemplate()->render(array('employee'  => $output[0], 'contract' => $output[1], 'type' => 'electric'));
  }

  /**
   * Handles generating an Natural Gas Renewal Report
   */
  public function gas(){
    $report = new RenewalSummary();
    $output = $report->controller($this->getConn(), 'gas');
    $this->setTemplate($this->getTwig()->loadTemplate('content.html.twig'));
    echo $this->getTemplate()->render(array('employee'  => $output[0], 'contract' => $output[1], 'type' => 'gas'));
  }

  /**
   * Handles generating an Book of Business report
   */
  public function book(){
    $report = new BookOfBusiness();
    $output = $report->controller($this->getConn());
    $this->setTemplate($this->getTwig()->loadTemplate('content.html.twig'));
    echo $this->getTemplate()->render(array('book_emp'  => $output[0], 'book_emp_bottom' => $output[1]));
  }

  public function commission(){
    $report = new MonthlyCommission();
    $output = $report->controller($this->getConn());
    $this->setTemplate($this->getTwig()->loadTemplate('content.html.twig'));
    echo $this->getTemplate()->render(array('commission'  => $output));
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