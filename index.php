<?php
/**
 * Main Controller of the PPS Report Generator application
 * Main flow:
 * 1: Create an instance of Twig
 * 2: Create an database connection
 * 3: Query data to construct the options menu
 * 4: Call out to generate the requested reports
 *
 * User: alexm
 * Date: 9/21/2016
 * Time: 11:48 AM
 */

include 'src/tools/queries.php';
include 'src/reports/ElectricitySummary.php';
include 'src/entities/Employee.php';
require_once 'lib/composer/vendor/twig/twig/lib/Twig/Autoloader.php';

/**
 * If we have no Twig instance create it here.
 * Setup the database connection
 */
if(!isset($twig)){
  Twig_Autoloader::register();
  $loader = new Twig_Loader_Filesystem('templates');
  $twig = new Twig_Environment($loader, array(
    'cache' => false,
    'debug' => true,
  ));
  $twig->addExtension(new Twig_Extension_Debug());
  $template = $twig->loadTemplate('index.html.twig');

  //Database connection
  $conn = init_database();
  if (!mysqli_ping($conn)) {
    //TODO: check connection and re-attempt then display error
    echo('Failed Database connection');
    exit;
  }
}

/**
 * If we have a database connection, carry on and
 * Query/Generate the required fields for the form
 */
if(isset($conn)){
  /**
   * If we have either not loaded the reps list or we need a fresh copy
   * Query the data and render the template otherwise carry on
   */
  //TODO: Evaluate this, not sure if it is needed or improves performance
  if(!isset($_POST['loaded']['reps']) || $_POST['loaded']['reps'] == false){
    $reps = query_reps($conn);
    $_POST['loaded']['reps'] = true;

    if(isset($template)){
      echo $template->render(array('reps' => $reps));
    }
  }

  /**
   * If we have either not loaded the min/max years or we need a fresh copy
   * Query the data and render the template otherwise carry on
   */
  //TODO: Evaluate this, not sure if it is needed or improves performance
  if(!isset($_POST['loaded']['dates']) || $_POST['loaded']['dates'] == false){
    $dates = query_date_ranges($conn);
    $_POST['loaded']['dates'] = true;
    if(isset($template)){
      echo $template->render(array('dates' => $dates));
    }
  }
}

/**
 * If we have a requested report, start here
 */
//TODO: Ensure we reset the $_POST['report'] variable to account for input without wanting a report
if(isset($conn) && (isset($_POST['report']) && !empty($_POST['report']))) {
  //Call to the specific report constructors
  //TODO: Send date barriers and rep list to report constructors
  if($_POST['report'] == 'electric'){
    $report = new ElectricitySummary();
  }

  else if($_POST['report'] == 'natural_gas'){
    $report = new NaturalGasSummary();
  }

  else if($_POST['report'] == 'commission'){
    $report = new MonthlyCommission();
  }

  else if($_POST['report'] == 'book'){
    $report = new BookOfBusiness();
  }
  
}