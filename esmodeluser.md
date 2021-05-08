>[Torna a Model](model.md) 
Esempio di modello con funzioni utili per la gestione degli utenti:
```PHP 
<?php

namespace App\Models;

//use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class User extends \Core\Model
{

    /**
     * Recupera il nome di tutti gli utenti
     *
     * @return array
     */
    static function getAllUsers(){
        $db = static::getDB();
        $rows = [];
        
        $sql = "SELECT username FROM users";
        $result = $db -> query($sql);
        if ($result -> num_rows > 0) {
            while ($row = $result -> fetch_array(MYSQLI_ASSOC)){
                array_push($rows,$row);
            }
        }
        $result -> free_result();
        return $rows;
    }
    /**
     * Recupera tutti i campi di tutti gli utenti
     *
     * @return array
     */
    static function  getAll(){
        $db = static::getDB();
        $rows = [];
        
        $sql = "SELECT * FROM users";
        $result = $db -> query($sql);
        if ($result -> num_rows > 0) {
            while ($row = $result -> fetch_array(MYSQLI_ASSOC)){
                array_push($rows,$row);
            }
        }
        $result -> free_result();
        return $rows;
    }
    /**
     * Recupera alcuni campi di un solo utente
     *
     * @return array
     */
    static function getOneUser($username){
        $db = static::getDB();
    	$row = [];
        $sql = "SELECT id, username, created_at FROM users WHERE username = ?";
        $stmt = $db->prepare($sql);
        if($stmt){
    		$stmt->bind_param("s", $param_username);
    		$param_username = $username; 
    		$stmt->execute();
    		$result = $stmt->get_result(); 
    	    if(!empty($result)){ 
    			$row = $result->fetch_assoc();
    		}
    		$stmt->close();
    	}
    	
        return $row;
    }
}
```
>[Torna a Model](model.md) 
