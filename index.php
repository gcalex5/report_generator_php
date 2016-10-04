<?php
/**
 * Index file handles creation of the SESSION and calling for reports
 *
 * User: alexm
 * Date: 9/21/2016
 * Time: 11:48 AM
 */

include 'src/tools/queries.php';
include 'src/reports/RenewalSummary.php';
include 'src/entities/Employee.php';
include 'src/entities/Contract.php';
include 'src/Controller/ReportController.php';
include 'lib/composer/vendor/twig/twig/lib/Twig/Autoloader.php';

session_start();
if(!isset($_POST['report'])){
  $controller = new ReportController();
  $_SESSION['loaded']['controller'];
}

if(isset($_POST['report'])){
  $controller = new ReportController();
  $controller->report_request();
}