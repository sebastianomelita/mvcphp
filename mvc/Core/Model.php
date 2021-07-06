<?php

namespace Core;

use PDO;
use Mysqli;
use App\Config;
use Core\RESTClient;

/**
 * Base model
 *
 * PHP version 5.4
 */
abstract class Model
{
	protected static function getDB()
    {
        static $db = null;
		
		if ($db === null) {
			// Create connection
			$db = new mysqli(Config::DB_HOST,Config::DB_USER,Config::DB_PASSWORD,Config::DB_NAME);

			// Check connection
			if ($db -> connect_errno) {
			  //echo "Non rieco a connettermi: " . $mysqli -> connect_error;
			  $message = "Non rieco a connettermi: " . $mysqli -> connect_error;
			  $file = "Model.php";
			  $line = "29";
			  errorHandler($level, $message, $file, $line);
			}
		}
		
        return $db;
    }
    
    protected static function getRESTClient()
    {
        static $httpClient = null;
		
		if ($httpClient  === null) {
			// Create client
			var_dump(file_exists("RESTClient.php"));
			$httpClient  = new RESTClient();

			/*
			if ($db -> connect_errno) {
			  //echo "Non rieco a connettermi: " . $mysqli -> connect_error;
			  $message = "Non rieco a connettermi: " . $mysqli -> connect_error;
			  $file = "Model.php";
			  $line = "29";
			  errorHandler($level, $message, $file, $line);
			}*/
		}
		
        return 	$httpClient;
    }
    /**
     * Get the PDO database connection
     *
     * @return mixed
     */
	 /*
    protected static function getDB()
    {
        static $db = null;

        if ($db === null) {
            $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8';
            $db = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);

            // Throw an Exception when an error occurs
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $db;
    }
	*/
}
