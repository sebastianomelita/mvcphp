>[Torna a Controller](controller.md) 

## Controller che comunica con un modello webservice

Il metodo 
```PHP
 getJSONResponse()
```
Restituisce la rappresentazione ad oggetti PHP della stringa JSON ricevuta. Questa può essere navigata per selezionare un nodo specifico da elaborare:
```PHP
$json = RESTClient::getJSONResponse();		//albero json completo memorizzato in $json
$drinkTitle = $json->drinks[0]->strDrink;       //selezione del nodo figlio strDrink dall'albero   
$drinkImg = $json->drinks[0]->strDrinkThumb;	//selezione del nodo figlio strDrinkThumb dall'albero
```
Il metodo di seguito esegue una **richiesta HTTP** e restituisce il JSON della risposta:

```PHP
// $method: GET, POST,PUT,
// $param: array asociativo con i parametri della richiesta (coppie nome, valore). Di default nessun parametro.
// $param: array asociativo con i campi dell'header (coppie nome, valore). Di default nessun parametro.
static function callAPI($method, $url, $param = false, $header = false)
```
Metodo per eseguire il **filtro** dei figli di un nodo della risposta json. I figli selezionati sono quelli con una radice comune nel nome del campo json.

```PHP
// $object: rappresentazione ad oggetti PHP del nodo padre
// $common: stringa con la radice comune dei vari campi (drink di drink1, drink2, drink3, ecc).
// $start: numerazione del suffisso della radice da cui partire (default 1, ad es. drink1)
static function extractCommon(&$buf, $object, $common, $start = 1)
```
Esempio di invocazione del filtro:
```PHP
$json->drinks[0]   //selezione del nodo padre dall'albero json completo memorizzato in $json
RESTClient::extractCommon($drinkIngredients, $json->drinks[0], "strIngredient");
```


```PHP
<?php

namespace App\Controllers;

use \Core\View;
use App\Models\RESTClient;
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

    // interrogazione di due webservice	
    public function portatedelgiornoAction()
    {
        $drinkTitle = "";
        $drinkImg = "";
        $drinkIngredients = array();
    	if($_SESSION['level'] == 0 || $_SESSION['level'] == 1 || $_SESSION['level'] == 2) {
            if(!RESTClient::callAPI("GET","www.thecocktaildb.com/api/json/v1/1/random.php")){
                $message = RESTClient::getRequestError();
                Error::errorHandler(1, $message, "", 0);
            }
            $json = RESTClient::getJSONResponse();
            $drinkTitle = $json->drinks[0]->strDrink;
            $drinkImg = $json->drinks[0]->strDrinkThumb;
            RESTClient::extractCommon($drinkIngredients, $json->drinks[0], "strIngredient");
    	}
    	
    	$mealTitle = "";
        $mealImg = "";
        $mealIngredients = array();
        if($_SESSION['level'] == 1 || $_SESSION['level'] == 2) {
            if(!RESTClient::callAPI("GET","https://www.themealdb.com/api/json/v1/1/random.php")){
                $message = RESTClient::getRequestError();
                Error::errorHandler(1, "$message", "", 0);
            }
            $json = RESTClient::getJSONResponse();
            $mealTitle = $json->meals[0]->strMeal;
            $mealImg = $json->meals[0]->strMealThumb;
            RESTClient::extractCommon($mealIngredients, $json->meals[0], "strIngredient");
            
            $path = 'Pub/index1.html';
            View::renderTemplate($path, [
                'drinkIngredients' => $drinkIngredients,
                'mealIngredients' => $mealIngredients,
                'drinkTitle' => $drinkTitle,
                'mealTitle' => $mealTitle,
                'drinkImg' => $drinkImg,
                'mealImg' => $mealImg
            ]);  
        }else{
            $path = 'Pub/index0.html';
            View::renderTemplate($path, [
                'drinkIngredients' => $drinkIngredients,
                'drinkTitle' => $drinkTitle,
                'drinkImg' => $drinkImg
            ]);  
            
        }
	}
	
    // interrogazione di un webservice (drinks)
    public function drinkAction()
    {
        $abc = $this->route_params["id"];
        
        $drinkTitle = "";
        $drinkImg = "";
        $drinks = array();
    	if($_SESSION['level'] == 1 || $_SESSION['level'] == 2) {
    	    if(!RESTClient::callAPI("GET","https://www.thecocktaildb.com/api/json/v1/1/search.php?f=".$abc)){
                $message = RESTClient::getRequestError();
                Error::errorHandler(1, $message, "", 0);
            }
            $json = RESTClient::getJSONResponse();
            if($json->drinks){
                foreach($json->drinks as $drink){
                    $drinkIngredients = array();
                    $drinkTitle = $drink->strDrink;
                    $drinkImg = $drink->strDrinkThumb;
                    RESTClient::extractCommon($drinkIngredients, $drink, "strIngredient");
                    $value = [
                        'drinkIngredients' => $drinkIngredients,
                        'drinkTitle' => $drinkTitle,
                        'drinkImg' => $drinkImg
                    ];
                    array_push($drinks, $value);
                }
            }
            $path = 'Pub/drinks.html';
            View::renderTemplate($path, [
                'drinks' => $drinks
            ]);  
    	}
	}
	
    // interrogazione di un webservice (meals)
    public function mealAction()
    {
        $abc = $this->route_params["id"];

    	$mealTitle = "";
        $mealImg = "";
        $meals = array();
        if($_SESSION['level'] == 1 || $_SESSION['level'] == 2) {
            if(!RESTClient::callAPI("GET","www.themealdb.com/api/json/v1/1/search.php?f=".$abc)){
                $message = RESTClient::getRequestError();
                Error::errorHandler(1, "$message", "", 0);
            }
            $json = RESTClient::getJSONResponse();
            if($json->meals){
                foreach($json->meals as $meal){
                    $mealIngredients = array();
                    $mealTitle =  $meal->strMeal;
                    $mealImg =  $meal->strMealThumb;
                    RESTClient::extractCommon($mealIngredients,  $meal, "strIngredient");
                    $value = [
                        'mealIngredients' => $mealIngredients,
                        'mealTitle' => $mealTitle,
                        'mealImg' => $mealImg
                    ];
                    array_push($meals, $value);
                }
            }
            $path = 'Pub/meals.html';
            View::renderTemplate($path, [
                'meals' => $meals
            ]);  
        }
	}
	
	// metodo stub che visualizza il menu dei drink
	public function drinksmenuAction(){
	    $range = range('A','Z');
	    $path = 'Pub/drinksmenu.html';
	    View::renderTemplate($path, [
                'range' => $range
            ]); 
	}
	
	// metodo stub che visualizza il menu dei meals
	public function mealsmenuAction(){
	    $range = range('A','Z');
	    $path = 'Pub/mealsmenu.html';
	    View::renderTemplate($path, [
                'range' => $range
            ]);    
	}
}
```
>[Torna a Controller](controller.md) 
