<?php
namespace Core;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class RESTClient
{
    private  $result = "";
    private  $username = "";
    private  $password = "";
    private  $curlerror = "";

     function  getResponse(){
        return $this->result;
    }
    
     function  getXMLResponse(){
        return new SimpleXMLElement($this->result);
    }
    
    function  getJSONResponse(){
        return json_decode($this->result);
    }
    
    function  getRequestError(){
        return $this->curlerror;
    }
    
    function  setAuth($user, $psw){
        $username = $user;
        $password = $psw;
    }
    
	static function  saveResponseOnFile($filename){
        file_put_contents($filename, this->result);
    }
    
   	function extractCommon(&$buf, $object, $common, $start = 1)
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

    function callAPI($method, $url, $param = false, $header = false)
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
        
        if ($this->username){
            // Optional Authentication:
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_USERPWD, $this->username.":".$this->password);
        }
		
		if($header){
            curl_setopt($s,CURLOPT_HTTPHEADER, $header);
        }
    
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER , false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        
        $this->result = curl_exec($curl);
    
        $status_code = @curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $this->curlerror = "Errore: ". curl_error($curl)." - Codice errore: ".curl_errno($curl)." - Status code: ".$status_code;

        curl_close($curl);
        return $this->result;
    }
}