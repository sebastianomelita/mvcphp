>[Torna a Model](model.md) 


```PHP 
<?php
namespace App\Models;

class RESTClient extends \Core\Model
{
    private static $result = "";
    private static $username = "";
    private static $password = "";
    private static $curlerror = "";

    static function  getResponse(){
        return self::$result;
    }
    
    static function  getXMLResponse(){
        return new SimpleXMLElement(self::$result);
    }
    
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

