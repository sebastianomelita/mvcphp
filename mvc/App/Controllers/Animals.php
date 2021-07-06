<?php

namespace App\Controllers;

use \Core\View;
use App\Models\Animal;
use App\Models\RESTClient;
use \Core\Error;

/**
 * Home controller
 *
 * PHP version 5.4
 */
class Animals extends \Core\Controller
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
	
	public function zooAction()
    {
        $sites = array();
        $title ="";

        // aggiungi gatti per tutti
    	if($_SESSION['level'] == 0 || $_SESSION['level'] == 1 || $_SESSION['level'] == 2) {
           $belve = Animal::gatti();
           array_push($sites, $belve);
    	}
    	// aggiungi cani per livello 1 o superiori
        if($_SESSION['level'] == 1 || $_SESSION['level'] == 2) {
            $belve = Animal::cani();
            array_push($sites, $belve);
        }
        // aggiungi volpi per livello 2 o superiori
        if($_SESSION['level'] == 2 ) {
            $belve = Animal::volpi();
            array_push($sites, $belve);
        }
        // visualizza
        $path = 'Animal/index.html';
        View::renderTemplate($path, [
            'sites' => $sites
        ]);  
	}
	
}
