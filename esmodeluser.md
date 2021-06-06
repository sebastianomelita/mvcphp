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
Esempio di codice PHP che recupera la lista delle pizze dalla tabella **Pizze** insieme agli ingredienti associati a ciascuna pizza contenuti nella tabella **Composizioni**:

```PHP 
static function getPizze()
    {
	    $db = static::getDB();  //recuperiamo un riferimento al database
        
        // query non preparata
	    $sql = "SELECT * FROM Pizze AS P ORDER BY P.Nome_pizza"; 
	    $result = $db -> query($sql);
	    
	    $pizze = array(); // inizializzo un array vuoto
        While($row = $result->fetch_array(MYSQLI_ASSOC)){
	    // recupero dei nodi figli
            $pingredienti = self::getIngredientipizza($row['Id_Pizza']);
            for($i=0; $i < count($pingredienti); $i++){
                $pingredienti[$i]['SurgelatoStr'] = ($pingredienti[$i]['Surgelato'])? "Si": "No";
            }
            
            // rappresentazione del nodo corrente
            $pizza = [
                        // inserimento dei campi del nodo corrente
			'Id_Pizza' => $row['Id_Pizza'],
                        'Nome_pizza' => $row['Nome_pizza'],
                        'Img' => $row['Img'],
                        'Costo' => $row['Costo'],
                        'Adatta_Celiaci' => $row['Adatta_Celiaci'],
                        'Adatta_IntolleantiLattosio' => $row['Adatta_IntolleantiLattosio'],
                        'ingredienti' => $pingredienti // inserimento della lista dei nodi figli
                    ];
            array_push($pizze, $pizza);
        }
        return $pizze;  
    }
```
Esempio di codice PHP che recupera gli ingredienti associati a ciascuna pizza contenuti nella tabella **Composizioni**:
```PHP 
static function getIngredientiPizza($id_pizza)
    {
	   $db = static::getDB();  //recuperiamo un riferimento al database
	   
	   // query preparata
	   $sql = "SELECT I.Id_Ingrediente, I.Nome, I.Surgelato, C.Quantita, C.Id_Composizione FROM Composizioni AS C, Ingredienti AS I  WHERE C.Id_Pizza = ? AND C.Id_Ingrediente = I.Id_Ingrediente;"; //query da preparare
	   $stmt = $db->prepare($sql);  //preparo la query
	   
	   $ingredienti = array(); // inizializzo un array vuoto
	   if($stmt) // controllo se la stmt e' piena (true)
	   {
			$stmt->bind_param("i", $param_idpizza); //assoocio i parametri da input ai ? della query preparata
			$param_idpizza = $id_pizza; //associo il valore del param al valore passato alla funzione $id_pizza
			$stmt->execute();
			if($stmt->error){
                printf("Error: %s.\n", $stmt->error);
            }
			$result = $stmt -> get_result();
			While($row = $result->fetch_assoc()) {
			     array_push($ingredienti, $row);
			}
			$stmt->close(); //chiudo la connessione
	   }else{
	       echo "Errore sintassi SQL!!!";
	   }
	   return $ingredienti; 
	}
	
```

    
>[Torna a Model](model.md) 
