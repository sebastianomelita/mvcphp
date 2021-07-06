<?php

namespace App\Controllers;

use \Core\View;
use App\Models\User;
use App\Models\Login;
use \Core\Error;

/**
 * Home controller
 *
 * PHP version 5.4
 */
class Users extends \Core\Controller
{

    private $logged = false;
	/**
     * Before filter
     *
     * @return void
     */
    protected function before()
    {
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
    
    protected function after()
    {
        //echo " (after)";
    }


    // metodo HTTP POST FORM ACTION
	public function doOneuserAction()
    {
        if(isset($_GET['username'])){			 // Controlla se il form è stato sottomesso
    		$user = Login::test_input($_GET["username"]);
    		$row = User::getOneUser($user);
    		if(!empty($row)){
    		    $path = 'Users/oneuser.html';  // rendering con template Twig
                View::renderTemplate($path, [
                	'row' => $row
                  ]);  
    		}else{
    		     $path = 'Home/index'.$_SESSION['level'].'.html';
    		     View::renderTemplate($path);
    		}
        }
    }

    // richiesta pagina web
	public function allusersAction()
    {
	  $rows = User::getAllUsers();
	  
	  $path = 'Users/allusers.html';  // rendering con template Twig
	  View::renderTemplate($path, [
			'rows' => $rows
		  ]);  
	}
	
	// richiesta pagina web
    public function oneuserAction()
    {
        $path = 'Users/oneuserform.html';
        View::renderTemplate($path);
	}
	
	// richiesta pagina web
	public function detailAction()
    {
	  $rows = User::getAll();
	  
	  $path = 'Users/alldetailed.html';  // rendering con template Twig
	  View::renderTemplate($path, [
			'rows' => $rows
		  ]);  
	}
}
