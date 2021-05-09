>[Torna a Model](model.md) 
>
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
$json->drinks[0]   //selezione del nodo padre dall'albero json completo memorizzato in $json

// $method: GET, POST,PUT,
// $object: rappresentazione ad oggetti PHP del nodo padre
// $common: stringa con la radice comune dei vari campi (drink di drink1, drink2, drink3, ecc).
// $start: numerazione del suffisso della radice da cui partire (default 1, ad es. drink1)
static function extractCommon(&$buf, $object, $common, $start = 1)
```
Esempio di invocazione del filtro:
```PHP
RESTClient::extractCommon($drinkIngredients, $json->drinks[0], "strIngredient");
```

Esempio completo del modello HTTPClient:
```PHP 
<?php
namespace App\Models;

class RESTClient extends \Core\Model
{
    private static $result = "";
    private static $username = "";
    private static $password = "";
    private static $curlerror = "";
    
    // Restituisce la rappresentazione sotto forma di stringa della risposta ricevuta
    static function  getResponse(){
        return self::$result;
    }
    
    // Restituisce la rappresentazione ad oggetti PHP della stringa XML ricevuta
    static function  getXMLResponse(){
        return new SimpleXMLElement(self::$result);
    }
    
    // Restituisce la rappresentazione ad oggetti PHP della stringa JSON ricevuta
    static function  getJSONResponse(){
        return json_decode(self::$result);
    }
    
    static function  getRequestError(){
        return self::$curlerror;
    }
    
    static function  setAuth($user, $psw){
        $username = $user;
        $password = $psw;
    }
    
	static function  saveResponseOnFile($filename){
        file_put_contents($filename, self::$result);
    }
    
    static function extractCommon(&$buf, $object, $common, $start = 1)
    {
        $property = $common.$start;
        $value = $object->$property; 
        while($value != "" && !is_null($value)){
            array_push($buf, $value);
            $start++;
            $property = $common.$start;
            if(isset($object->$property)){
                $value = $object->$property; 
            }else{
                $value = "";
            }
        }
    }
	
    // Method: POST, PUT, GET etc
    // Data: array("param" => "value") ==> index.php?param=value
	// Header: array("Accept" => "application/json", "Content-Type" => "multipart/form-data"); 

    static function callAPI($method, $url, $param = false, $header = false)
    {
        $curl = curl_init();
    
        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
    
                if ($param)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($param)
                    $url = sprintf("%s?%s", $url, http_build_query($param));
        }
        
        if (self::$username){
            // Optional Authentication:
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_USERPWD, self::$username.":".self::$password);
        }
		
	if($header){
            curl_setopt($s,CURLOPT_HTTPHEADER, $header);
        }
    
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER , false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        
        self::$result = curl_exec($curl);
    
        $status_code = @curl_getinfo($curl, CURLINFO_HTTP_CODE);
        self::$curlerror = "Errore: ". curl_error($curl)." - Codice errore: ".curl_errno($curl)." - Status code: ".$status_code;

        curl_close($curl);
        return self::$result;
    }
}
```

>[Torna a Model](model.md) 

