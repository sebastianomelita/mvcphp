>[Torna a Model](model.md) 

### **Creazione di una albero di dati da un database relazionale**

I **dati da visualizzare** nella vista devono essere recuperati **tutti nel modello**. 

La **rappresentazione** delle informazioni **nella vista** spesso ha una **struttura ad albero** (ad esempio quella del DOM HTML) che deve essere ricostruita nella **lista dei dati** che il modello passa alla vista. 

Ogni **nodo** dell'albero è un **oggetto** o un **array associativo**, che, oltre alle informazioni proprie di quel livello, contiene la **lista con i nodi** del livello ad esso **inferiore**. 

Ad esempio un catalogo di pizze può essere visto come una **lista** di pizze dove ogni pizza contiene la **lista** degli ingredienti in essa contenuti. La lista, sotto forma di **array associativo**, è proprio il modo con cui vengono restituite le righe (tuple) di una generica query.

**Per ogni nodo**, tutte le informazioni del **livello inferiore** si possono recuperare con una **seconda query** che, di nuovo, seleziona tutte quelle che posseggono una **proprietà** che le lega ad un **nodo padre**. Si potrebbero, ad esempio, recuperare tutti **gli ingredienti** di una certa pizza appartenente alla categoria delle pizze vegetariane. Se si vuole il catalogo con gli ingredienti delle pizze vegetariane, questa operazione va ripetuta per tutte le pizze della categoria.

**In sostanza**, per ottenere le informazioni sui nodi di un certo livello si deve:
1. **eseguire la query** che restituisce la lista dei nodi definendo come vincolo l'id dell'eventuale nodo padre comune 
2. **iterare sulla lista delle righe** restituite, inserirle nei campi di un oggetto o di un array associativo che **rappresenta il nodo**
3. **eseguire una seconda query**, utilizzando come chiave l'identificativo del nodo in esame, che ricava la lista delle informazioni correlate a quel nodo e salvarla in **ulteriore campo** dell'oggetto o dell'array associativo che rappresenta il nodo in esame.
4. inserire l'oggetto o l'array associativo che rappresenta il nodo corrente nella lista dei nodi (un'altro array associativo)

Ad esempio si prepara tramite un **array associativo** una lista di pizze **inizialmente vuota**:
1. **con una query più esterna** si possono selezionare le pizze di una certa categoria con le informazioni che ad esse appartengono come nome, costo e categoria
2. **la lista delle righe viene scandita con un ciclo** che inserisce nome, costo e categoria nei campi dell'array associativo della pizza corrente
3.  **all'interno del ciclo** viene eseguita anche **una seconda query** per recuperare la lista degli ingredienti corrispondenti a quell'id
4. il risultato è **un'altra lista di righe** che può essere, a sua volta, inserita all'interno della rappresentazione del **nodo corrente** (array asociativo della pizza corrente) 
5. l'**array associativo** che rappresenta la pizza corrente, cioè la rappresentazione del nodo corrente, viene inserito all'interno della lista delle pizze completando le informazioni che devono essere raccolte riguardo una singola pizza.

In realtà il **processo di composizione** delle due query per recuperare le informazioni sul nodo corrente e quelle sui figli del nodo corrente si sarebbe potuto realizzare, alternativamente, all'interno del controller. In questo caso il modello avrebbe fornito due liste separate che andavano composte nel controller. Si è preferito l'approccio di fare la composizione nel modello perchè, essendo il punto più vicino alla sorgente dei dati, si evitano passaggi in più con duplicazione delle informazioni.

Per quanto riguarda le **operazioni di inserimento**, invece, il punto più vicino alla fonte dei dati (il form di inserimento) è il controller e la composizione si è preferita eseguirla lì.

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
