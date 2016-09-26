<?php
/**
 *
 * Handles instancing of Twig and recieving the Ajax POST request to generate specific reports
 *
 * User: alexm
 * Date: 9/21/2016
 * Time: 11:48 AM
 */

include 'src/tools/queries.php';
include 'src/reports/ElectricitySummary.php';
require_once 'lib/composer/vendor/twig/twig/lib/Twig/Autoloader.php';

/**
 * If we have no Twig instance create it here.
 * Setup the database connection
 */
if(!isset($twig)){
  Twig_Autoloader::register();
  $loader = new Twig_Loader_Filesystem('templates');
  $twig = new Twig_Environment($loader, array(
    'cache' => 'templates/cache',
  ));
  $template = $twig->loadTemplate('index.html.twig');

  //Disable caching for development
  echo $template->render(array('cache' => false, 'auto_reload' => true));

  //Database connection
  $conn = init_database();
  if (!mysqli_ping($conn)) {
    //TODO: check connection and re-attempt then display error
    echo('Failed connection');
    exit;
  }
}

/**
 * If we have a database connection, carry on and
 * Query/Generate the required fields for the form
 */
if(isset($conn)){
  //Reps
  //Min/Max date fields


}

/**
 * If we have a requested report, start here
 */
if(isset($conn) && (isset($_POST['report']) && !empty($_POST['report']))) {
    //Call to the specific report constructors
    //TODO: Send date barriers and rep list to report constructors
    if($_POST['report'] == 'electric'){
      $report = new ElectricitySummary();
    }

    if($_POST['report'] == 'natural_gas'){
      $report = new NaturalGasSummary();
    }

    if($_POST['report'] == 'commission'){
      $report = new MonthlyCommission();
    }

    if($_POST['report'] == 'book'){
      $report = new BookOfBusiness();
    }
    //TODO: convert outputted report data to JSON and send to frontend
}