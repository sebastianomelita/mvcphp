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
