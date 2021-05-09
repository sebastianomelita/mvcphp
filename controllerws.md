>[Torna a Controller](controller.md) 

## Controller che comunica con un modello webservice

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
```
>[Torna a Controller](controller.md) 
