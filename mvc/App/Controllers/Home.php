<?php

namespace App\Controllers;

use \Core\View;
use App\Models\Animals;
use App\Models\RESTClient;

/**
 * Home controller
 *
 * PHP version 5.4
 */
class Home extends \Core\Controller
{

    private $logged = false;
	/**
     * Before filter
     *
     * @return void
     */
    protected function before()
    {
        //echo "(before) ";
        //return false;
		$logged = false;
		session_start();
		if (isset($_SESSION['username'])) {
			$logged = true;
		}else{
			//Redirect to login page
			View::renderTemplate('Login/login.html');
		}
		return $logged;
    }

    /**
     * After filter
     *
     * @return void
     */
    protected function after()
    {
        //echo " (after)";
    }

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
	  $path = 'Home/index'.$_SESSION['level'].'.html';
	  View::renderTemplate($path);  
	}
}
