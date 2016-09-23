<?php
/**
 * Created by PhpStorm.
 * User: alexm
 * Date: 9/21/2016
 * Time: 11:48 AM
 */

require_once 'lib/composer/vendor/twig/twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader, array(
  'cache' => 'templates/cache',
));


$template = $twig->loadTemplate('home.twig');
echo $template->render(array());