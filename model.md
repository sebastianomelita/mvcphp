>[Torna a MVC](mvcindex.md) 
## **Model**

I modelli che consideriamo noi sono sostanzialmente due: 
-	accesso al DB per leggere o scrivere. Serve a recuperare, mediante una query SQL, quelle informazioni che devono essere organizzate in strutture PHP adatte ad una loro visualizzazione in una pagina o a alla composizione di una stringa JSON.
-	accesso a web service multipli per filtraggio dei campi JSON e per la loro aggregazione in strutture PHP adatte ad una loro visualizzazione in una pagina o a alla composizione di una stringa JSON.
 
![model](model.png)


Tutti i modelli ereditano da una classe padre l’accesso al database recuperabile con la chiamata 
```PHP 
$db = static::getDB();
```
Occorre precisare che le classi del modello sono tutte statiche nel senso che possiedono metodi statici e proprietà statiche. I metodi statici sono dichiarati anteponendo il qualificatore static davanti il nome del metodo, ad es:
static function getHashedPsw($username,&$authlevel){
Le proprietà statiche sono dichiarate anteponendo il qualificatore static davanti il nome della proprietà:
```PHP 
private static $result = "";
```
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
>[Torna a MVC](mvcindex.md) 
