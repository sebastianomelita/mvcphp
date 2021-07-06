<?php

/**
 * Front controller
 *
 * PHP version 5.4
 */

/**
 * Composer
 */
require '../vendor/autoload.php';


/**
 * Twig
 */
//Twig_Autoloader::register();


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
//$router->add('login/login/do-login', ['controller' => 'Login', 'action' => 'doLogin']);
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('{controller}/{action}/{id:\d+}');
$router->add('{controller}/{action}/{id:\d+}/{jd:\d+}');
$router->add('{controller}/{action}/{id:\w+}');
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);
 //Core\Error::errorHandler("1", print_r($_SERVER['QUERY_STRING']), "pippo", "4");    
$router->dispatch($_SERVER['QUERY_STRING']);
