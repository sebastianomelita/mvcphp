<?php

namespace App\Controllers;

use \Core\View;
use App\Models\Pub;
use \Core\Error;

/**
 * Home controller
 *
 * PHP version 5.4
 */
class Pubs extends \Core\Controller
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
	
	public function portatedelgiornoAction()
    {
        $drinkInfo = Pub::drinkdelgiorno();
    	
        if($_SESSION['level'] == 1 || $_SESSION['level'] == 2) {
            $mealInfo = Pub::pastodelgiorno();
            
            $path = 'Pub/index1.html';
            View::renderTemplate($path, [
                'drink' => $drinkInfo,
                'meal' => $mealInfo
            ]);  
        }else{
            $path = 'Pub/index0.html';
            View::renderTemplate($path, [
                'drink' => $drinkInfo
            ]);  
        }
	}
	
	public function drinkAction()
    {
        $abc = $this->route_params["id"];
        
    	if($_SESSION['level'] == 1 || $_SESSION['level'] == 2) {
    	    $drinks = Pub::drink($abc);
            $path = 'Pub/drinks.html';
            View::renderTemplate($path, [
                'drinks' => $drinks
            ]);  
    	}
	}
	
	public function mealAction()
    {
        $abc = $this->route_params["id"];

        if($_SESSION['level'] == 1 || $_SESSION['level'] == 2) {
            $meals = Pub::meal($abc);
            $path = 'Pub/meals.html';
            View::renderTemplate($path, [
                'meals' => $meals
            ]);  
        }
	}
	
	public function drinksmenuAction(){
	    $range = range('A','Z');
	    $path = 'Pub/drinksmenu.html';
	    View::renderTemplate($path, [
                'range' => $range
            ]); 
	}
	
	public function mealsmenuAction(){
	    $range = range('A','Z');
	    $path = 'Pub/mealsmenu.html';
	    View::renderTemplate($path, [
                'range' => $range
            ]);    
	}
}
