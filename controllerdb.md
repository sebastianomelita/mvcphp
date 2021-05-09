>[Torna a Controller](controller.md) 

## Controller che comunica con un modello DB

Tutti i modelli ereditano da una classe padre l’accesso al database che nelle **classi figlie** è recuperabile con la chiamata:
```PHP 
$db = static::getDB();
```

Esempio **completo** di controller che definisce alcune funzioni di gestione degli utenti prelevate da un DB:

```PHP
/*
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

    // Action di un form
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
	
    // richiesta pagina di un form (metodo stub)
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
```
>[Torna a Controller](controller.md)
