>[Torna a Model](model.md) 
>
Il metodo 
```PHP
 getJSONResponse()
```
Restituisce la rappresentazione ad oggetti PHP della stringa JSON ricevuta. Questa può essere navigata per selezionare un nodo specifico da elaborare:
```PHP
$rc = static::getRESTClient();
$rc->callAPI("GET","https://api.thecatapi.com/v1/images/search");
$json = $rc->getJSONResponse();		//albero json completo memorizzato in $json
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

Esempio completo del modello HTTPClient:
```PHP 

<?php

namespace App\Models;

use \Core\View;
use App\Models\Animal;
use \Core\Error;

class Animal extends \Core\Model
{
    static function gatti()
    {
        $rc = static::getRESTClient();
        
        if(!$rc->callAPI("GET","https://api.thecatapi.com/v1/images/search")){
                $message = $rc->getRequestError();
                Error::errorHandler(1, $message, "", 0);
        }
        $json = $rc->getJSONResponse();
        
        $out = [
                    'image' => $json[0]->url,
                    'title' => "Gatti"
                ];

        return $out;
	}
	
    static function cani()
    {
        $rc = static::getRESTClient();
        
        if(!$rc->callAPI("GET","https://dog.ceo/api/breeds/image/random")){
                $message = $rc->getRequestError();
                Error::errorHandler(1, $message, "", 0);
        }
        $json = $rc->getJSONResponse();
        
        $out = [
                    'image' => $json->message,
                    'title' => "Cani"
                ];

        return $out;
	}
	
    static function volpi()
    {
        $rc = static::getRESTClient();
        
        if(!$rc->callAPI("GET","https://randomfox.ca/floof")){
                $message = $rc->getRequestError();
                Error::errorHandler(1, $message, "", 0);
        }
        $json = $rc->getJSONResponse();
        
        $out = [
                    'image' => $json->image,
                    'title' => "Volpi"
                ];

        return $out;
	}
}


```
Esempio che illustra l'utilizzo della funzione ```extractCommon()```:

```PHP
<?php

namespace App\Models;

use \Core\View;
use App\Models\Pub;
use \Core\Error;

class Pub extends \Core\Model
{
    static function drinkdelgiorno()
    {
        $rc = static::getRESTClient();
        
        if(!$rc->callAPI("GET","www.thecocktaildb.com/api/json/v1/1/random.php")){
                $message = $rc->getRequestError();
                Error::errorHandler(1, $message, "", 0);
        }
        $drinkIngredients = Array();
        $json = $rc->getJSONResponse();
        $drinkTitle = $json->drinks[0]->strDrink;
        $drinkImg = $json->drinks[0]->strDrinkThumb;
        $rc->extractCommon($drinkIngredients, $json->drinks[0], "strIngredient");
    	
        if(!$rc->callAPI("GET","https://www.themealdb.com/api/json/v1/1/random.php")){
            $message = $rc->getRequestError();
            Error::errorHandler(1, "$message", "", 0);
        }
        $mealIngredients = Array();
        $json = $rc->getJSONResponse();
        $mealTitle = $json->meals[0]->strMeal;
        $mealImg = $json->meals[0]->strMealThumb;
        $rc->extractCommon($mealIngredients, $json->meals[0], "strIngredient");
        
        $out = [
            'drinkIngredients' => $drinkIngredients,
            'drinkTitle' => $drinkTitle,
            'drinkImg' => $drinkImg
        ]; 
        
        return $out;
	}
	
		
    static function drink($abc)
    {
        $rc = static::getRESTClient();
        
        $drinks = array();
 	    if(!$rc->callAPI("GET","https://www.thecocktaildb.com/api/json/v1/1/search.php?f=".$abc)){
            $message = $rc->getRequestError();
            Error::errorHandler(1, $message, "", 0);
        }
        $json = $rc->getJSONResponse();
        if($json->drinks){
            foreach($json->drinks as $drink){
                $drinkIngredients = array();
                $drinkTitle = $drink->strDrink;
                $drinkImg = $drink->strDrinkThumb;
                $rc->extractCommon($drinkIngredients, $drink, "strIngredient");
                $value = [
                    'drinkIngredients' => $drinkIngredients,
                    'drinkTitle' => $drinkTitle,
                    'drinkImg' => $drinkImg
                ];
                array_push($drinks, $value);
            }
        }
        $out =$drinks; 
        
        return $out;
	}
}
```
>[Torna a Model](model.md) 

