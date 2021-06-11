>[Torna a Controller](controller.md) 

## Controller che comunica con un modello DB

![modeldb](modeldb.png) 

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
La **rotta** ```pizze/listapizze/```, individuata nella richiesta HTTP, invoca il **metodo** ```listapizzeAction()``` dell'**oggetto** ```Pizze``` instanziato al volo (dinamicamente) al momento dell'arrivo della richiesta:

```PHP 
public function listapizzeAction()
{
	$pizze = Pizza::getPizze();
        $path = 'Pizza/listapizze'.$_SESSION['level'].'.html';
        View::renderTemplate($path, [
            'pizze' => $pizze
        ]);  
}
```
Il risultato è una pagina HTML che stampa l'elenco di tutte le pizze con nome, foto, dettagli della pizza e lista degli ingredienti di ciascuna. La lista dei dati è recuperata **dal modello** con ```Pizza::getPizze()``` ed è passata al template nel formato richiesto per il passaggio, cioè  includendola nell'array associativo 
```PHP 
[
    'pizze' => $pizze
]
```
L'array associativo possiede una **struttura ad albero** che combacia con quella del DOM della pagina da visualizzare, in maniera tale che, leggendo nodo per nodo l'array, si riempie, elemento per elemento, il template HTML della pagina.

>[Torna a Controller](controller.md)
