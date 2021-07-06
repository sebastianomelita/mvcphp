<?php

namespace App\Controllers;

use \Core\View;
use App\Models\Login;
use \Core\Error;

/**
 * Home controller
 *
 * PHP version 5.4
 */
class Logins extends \Core\Controller
{
	/**
     * Before filter
     *
     * @return void
     */
    protected function before()
    {
        //echo "(before) ";
        //return false;
        //print_r($_POST);
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
	// metodo HTTP POST FORM ACTION
	public function doRegisterAction(){
	    session_start();
		$username = $password = $confirm_password = $authlevel = "";
		$reg_err = "";
		if($_SERVER["REQUEST_METHOD"] == "POST"){//Processamento del form
				$username = Login::test_input($_POST["username"]);
				$authlevel = (int) Login::test_input($_POST["authlevel"]);
				if(Login::validate_usr($reg_err, $username)){
					$password = Login::test_input($_POST["password"]); 
					$confirm_password = Login::test_input($_POST["confirm_password"]);
					if(Login::validate_psw($reg_err, $password, $confirm_password )){
						if(Login::insert_user($username,$password,$authlevel)){
							//Redirect to login page
							View::renderTemplate('Login/login.html');
						} else{
							$message = "Utente non trovato.";
							$path = "Login/loginerr.html";
							View::renderTemplate($path, [
								'msg'    => $message,
								'title' => "Errore"
							  ]);  
						}
					}else{
						$message = "Validazione password: ".$reg_err;
						$path = "Login/loginerr.html";
						View::renderTemplate($path, [
								'msg'    => $message,
								'title' => "Errore"
							  ]); 
					}
				}else{
					$message = "Errore validate user: ".$reg_err;
					//$file = "Login.php";
					//$line = "59";
					//Error::errorHandler(1, $message, $file, $line);
					$path = "Login/loginerr.html";
					View::renderTemplate($path, [
								'msg'    => $message,
								'title' => "Errore"
							  ]); 
				}
		}
	}
	// metodo HTTP POST FORM ACTION
	public function doLoginAction(){
	    session_start();
	    $authLevel = 0;
		if(isset($_POST['submit'])){// Controlla se il form è stato sottomesso
			$sbmtuname = Login::test_input($_POST["username"]);
			$sbmtpassword = Login::test_input($_POST["password"]);
			$result = password_verify($sbmtpassword, Login::getHashedPsw($sbmtuname,$authLevel)); //Controlla hash
			if($result){
				session_regenerate_id();  //contromisura ad attacco session fixation
				$_SESSION['username'] = $sbmtuname;   // Salvataggio dello stato
				$_SESSION['level'] = $authLevel;
				$_SESSION['active'] = true;
				$path = 'Home/index'.$_SESSION['level'].'.html';  // rendering con template Twig
				//$path = 'Home/index'.$_SESSION['level'].'.php'; // rendering con template PHP
				View::renderTemplate($path);
			}else{
				$_SESSION['active'] = false;
				$_SESSION['level'] = 0;
				//Redirect to login page
				View::renderTemplate('Login/login.html');
			}
		}
	}
	
    /**
     * Show the index page
     *
     * @return void     */
    // richiesta pagina web
    public function loginAction()
    {
	  $path = 'Login/login.html';
	  View::renderTemplate($path);  
	}
	
	// richiesta pagina web
	public function registerAction()
    {
	  $path = 'Login/register.html';
	  View::renderTemplate($path);  
	}
	
	// richiesta pagina web
	public function logoutAction(){
        // Initialize the session
        session_start();
        // Libera (dealloca) tutte le variabili di sessione
        session_unset();
        // Distrugge tutti I dati registrati in una sessione.
        session_destroy();
        //Redirect to login page
		View::renderTemplate('Login/login.html');
    }
}
