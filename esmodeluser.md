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
```PHP 
static function getPizze()
    {
	    $db = static::getDB();  //recuperiamo un riferimento al database
        
        // query non preparata
	    $sql = "SELECT * FROM Pizze AS P ORDER BY P.Nome_pizza"; 
	    $result = $db -> query($sql);
	    
	    $pizze = array(); // inizializzo un array vuoto
        While($row = $result->fetch_array(MYSQLI_ASSOC)){
            $pingredienti = self::getIngredientipizza($row['Id_Pizza']);
            for($i=0; $i < count($pingredienti); $i++){
                $pingredienti[$i]['SurgelatoStr'] = ($pingredienti[$i]['Surgelato'])? "Si": "No";
            }
            
            //array_shift($ingredienti); //elimina il primo elemento vuoto
            // composizione array associativo della vista
            $pizza = [
                        'Id_Pizza' => $row['Id_Pizza'],
                        'Nome_pizza' => $row['Nome_pizza'],
                        'Img' => $row['Img'],
                        'Costo' => $row['Costo'],
                        'Adatta_Celiaci' => $row['Adatta_Celiaci'],
                        'Adatta_IntolleantiLattosio' => $row['Adatta_IntolleantiLattosio'],
                        'ingredienti' => $pingredienti
                    ];
            array_push($pizze, $pizza);
        }
        return $pizze;  
    }
    ```
>[Torna a Model](model.md) 
