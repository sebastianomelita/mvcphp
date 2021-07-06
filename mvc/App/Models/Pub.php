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
	
	static function pastodelgiorno()
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
            'mealIngredients' => $mealIngredients,
            'mealTitle' => $mealTitle,
            'mealImg' => $mealImg
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
	
	static function meal($abc)
    {
        $rc = static::getRESTClient();

    	$mealTitle = "";
        $mealImg = "";
        $meals = array();
        if(!$rc->callAPI("GET","www.themealdb.com/api/json/v1/1/search.php?f=".$abc)){
            $message = $rc->getRequestError();
            Error::errorHandler(1, "$message", "", 0);
        }
        $json = $rc->getJSONResponse();
        if($json->meals){
            foreach($json->meals as $meal){
                $mealIngredients = array();
                $mealTitle =  $meal->strMeal;
                $mealImg =  $meal->strMealThumb;
                $rc->extractCommon($mealIngredients,  $meal, "strIngredient");
                $value = [
                    'mealIngredients' => $mealIngredients,
                    'mealTitle' => $mealTitle,
                    'mealImg' => $mealImg
                ];
                array_push($meals, $value);
            }
        }
        
        $out = $meals;  
        
        return $out;
	}
}
