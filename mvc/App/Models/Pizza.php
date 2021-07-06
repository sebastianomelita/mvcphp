<?php

namespace App\Models;

use \Core\View;
use App\Models\Pizza;
use \Core\Error;

class Pizza extends \Core\Model
{
    
    static function getIngrediente($id_ingrediente)
    {
	    $db = static::getDB();  //recuperiamo un riferimento al database
	   
	    // query preparata
	    $sql = "SELECT * FROM Ingredienti AS I WHERE I.Id_Ingrediente = ?"; 
	    $stmt = $db->prepare($sql);  //preparo la query
	    
	    $stmt->bind_param("i", $param_idingrediente); 
        $param_idingrediente = $id_ingrediente; 
        $stmt->execute();
        if($stmt->error){
            printf("Error: %s.\n", $stmt->error);
        }
        $result = $stmt -> get_result();
        if($result) // controllo se la stmt e' piena (true)
        {
        	$row = $result->fetch_assoc();
            $ingrediente = [
                    'Id_Ingrediente' => $row['Id_Ingrediente'],
                    'Nome' => $row['Nome'],
                    'Surgelato' => $row['Surgelato'],
                    'SurgelatoStr' => ($row['Surgelato'])? "Si": "No",
                    'Checked'  => ""
                ];
        }
        return $ingrediente; 
	}
    
    static function getIngredienti()
    {
	   $db = static::getDB();  //recuperiamo un riferimento al database
	   
	   // query preparata
	   $sql = "SELECT * FROM Ingredienti AS I ORDER BY I.nome"; 
	   $result = $db->query($sql);
	   if($result) // controllo se la stmt e' piena (true)
	   {
			$ingredienti = array(); // inizializzo un array vuoto
			array_push($ingredienti, ["Id_Ingrediente" => "", "Nome" => "", "Surgelato" => "", "SurgelatoStr" => ""]);
			While($row = $result->fetch_assoc()) {
			    $ingrediente = [
                        'Id_Ingrediente' => $row['Id_Ingrediente'],
                        'Nome' => $row['Nome'],
                        'Surgelato' => $row['Surgelato'],
                        'SurgelatoStr' => ($row['Surgelato'])? "Si": "No",
                        'Checked'  => ""
                    ];
			    array_push($ingredienti, $ingrediente);
			}
			return $ingredienti; 
	   }
	}
	
	static function getIngredientickecked($id_pizza,$param,$n)
    {
	   $pingredienti = self::getIngredientiPizza($id_pizza); //Id_Ingrediente, nome, Quantita
	   $ingredienti = self::getIngredienti(); //Id_Ingrediente, Nome, Surgelato, SurgelatoStr, Checked
	   
	   $arr = array();
	   $count = count($ingredienti);
	   for($i=0; $i < $n; $i++){
	       $pi = $i + 1;
	       $arr[$i]['value'] = $param.$pi;
	       $arr[$i]['ingredienti'] = $ingredienti;
	       $arr[$i]['quantita'] = 0;
    	   if($i < count($pingredienti)){
    	       $arr[$i]['quantita'] = $pingredienti[$i]['Quantita'];
    	       $curring_id = $pingredienti[$i]['Id_Ingrediente'];
    	       for($j=0; $arr[$i]['ingredienti'][$j]['Id_Ingrediente'] != $curring_id && $j < $count; $j++); // cerco l'id 
    	       if($arr[$i]['ingredienti'][$j]['Id_Ingrediente'] == $curring_id){ // se lo trovo
    	           $arr[$i]['ingredienti'][$j]['Checked'] = "selected";  // lo seleziono
    	       }
    	   }  
	   }
	   return $arr;
	}
    
    // nella stampa devono essere serializzati su array associativi
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
	
	static function addIngrediente($ingrediente)
    {
	    $db = static::getDB();  //recuperiamo un riferimento al database
        
        $id = null;
        if(self::validate_ingrediente($err, $ingrediente['nome'])){
            $stmt = $db->prepare("INSERT INTO Ingredienti (Nome, Surgelato) VALUES (?, ?)");
            $stmt->bind_param("si", $nome_param,$surgelato_param);
            $nome_param = $ingrediente['nome'];
            $surgelato_param = $ingrediente['surgelato'];
            // set parameters and execute
            $stmt->execute();
            $id = $stmt->insert_id; //auto_increment in tabella
            $stmt->close();
        }
        return $id;
	}
	
	static function validate_ingrediente(&$err,$ingrediente){
		$ok=false;
		$db = static::getDB();
		
		if(empty($ingrediente)){
			$err = "Prego inserire un nome ingrediente.";
		}else{
			$sql = "SELECT Id_Ingrediente FROM Ingredienti WHERE Nome = ?";
			$stmt = $db->prepare($sql);
			if($stmt){
				$stmt->bind_param ("s", $param_ingrediente);
				$param_ingrediente = $ingrediente;
				if($stmt->execute()){
					$stmt->store_result();
					if ($stmt->num_rows == 0){
						$ok = true;
					}else{
						$err = "Questo ingrediente  gi in uso."; 
					} 
				}else{
					$err = "Errore non definito. Riprovare pi tardi.";
				}
				$stmt->close();
			}
	   }
	   return $ok;
	}
	
	static function validate_pizza(&$err,$pizza){
		$ok=false;
		$db = static::getDB();
		if(empty($pizza)){
			$err = "Prego inserire un nome pizza.";
		}else{
			$sql = "SELECT Id_Pizza FROM Pizze WHERE Nome_pizza = ?";
			$stmt = $db->prepare($sql);
			if($stmt){
				$stmt->bind_param ("s", $Nome_pizza_param);
				$Nome_pizza_param = $pizza;
				if($stmt->execute()){
					$stmt->store_result();
					if ($stmt->num_rows == 0){
						$ok = true;
					}else{
						$err = "Questa pizza siste gi."; 
					} 
				}else{
					$err = "Errore non definito. Riprovare pi tardi.";
				}
				$stmt->close();
			}
	   }
	   return $ok;
	}

	static function addIngredientePizza($id_pizza, $ing)
    {
	    $db = static::getDB();  //recuperiamo un riferimento al database
	    
	    $sql = "INSERT INTO Composizioni(Id_Pizza, Id_Ingrediente, Quantita) VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("isd", $id_Pizza_param, $id_Ingrediente_param, $quantità_param);
        
        $id = null;
        $id_Pizza_param = $id_pizza;
        // set parameters and execute
        $id_Ingrediente_param = $ing['Id_Ingrediente'];
        $quantità_param = $ing['Quantita'];
        $stmt->execute();
        $id = $stmt->insert_id;
        if($stmt->error){
            printf("Error: %s.\n", $stmt->error);
        }

        $stmt->close();
        return $id;
	}
	
	static function updateIngredientePizza($Id_Composizione, $id_pizza, $ing)
    {
	    $db = static::getDB();  //recuperiamo un riferimento al database
	   
	    $sql = "UPDATE Composizioni SET Id_Pizza = ?, Id_Ingrediente = ?, Quantita = ? WHERE Id_Composizione = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("isdi", $id_Pizza_param, $id_Ingrediente_param, $quantità_param, $Id_Composizione_param);
        
        $id_Pizza_param = $id_pizza;
        // set parameters and execute
        $id_Ingrediente_param = $ing['Id_Ingrediente'];
        $quantità_param = $ing['Quantita'];
        $Id_Composizione_param = $Id_Composizione;
        $stmt->execute();
        if($stmt->error){
            printf("Error: %s.\n", $stmt->error);
        }

        $stmt->close();
	}
	
	static function removeIngredientiPizza($id_pizza)
    {
	    $db = static::getDB();  //recuperiamo un riferimento al database
	   
        $stmt = $db->prepare("DELETE FROM Composizioni WHERE Id_Pizza = ?");
        $stmt->bind_param("i", $id_pizza_param);
        
        // set parameters and execute
        $id_pizza_param = $id_pizza;
        $stmt->execute();
        if($stmt->error){
            printf("Error: %s.\n", $stmt->error);
        }

        $stmt->close();
	}
	
	static function removePizza($id_pizza)
    {
	    $db = static::getDB();  //recuperiamo un riferimento al database
	   
        $stmt = $db->prepare("DELETE FROM Pizze WHERE Id_Pizza = ?");
        $stmt->bind_param("i", $id_pizza_param);
        // set parameters and execute
        $id_pizza_param = $id_pizza;
        $stmt->execute();
        if($stmt->error){
            printf("Error: %s.\n", $stmt->error);
        }
        $stmt->close();
	}
	
	static function addPizza($pizza)
    {
	    $db = static::getDB();  //recuperiamo un riferimento al database
	    
	    $id = null;
	    if(self::validate_Pizza($err, $pizza['Nome_pizza'])){
    	    $sql = "INSERT INTO Pizze(Nome_pizza, Costo, PesoPizza, Adatta_Celiaci, Adatta_IntolleantiLattosio, Img) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("sdisss", $nome_pizza_param, $costo_param, $pesoPizza_param, $adatta_Celiaci_param, $adatta_IntolleantiLattosio_param, $img_param);
            
            // set parameters and execute
            $nome_pizza_param = $pizza['Nome_pizza'];
            $costo_param = $pizza['Costo'];
            $pesoPizza_param = $pizza['PesoPizza'];
            $adatta_Celiaci_param = $pizza['Adatta_Celiaci'];
            $adatta_IntolleantiLattosio_param = $pizza['Adatta_IntolleantiLattosio'];
            $img_param = $pizza['Img'];
            $stmt->execute();
            if($stmt->error){
                printf("Error: %s.\n", $stmt->error);
            }
            $id = $stmt->insert_id;
            $stmt->close();
	    }
        return $id;
	}
	
	static function updatePizza($id_pizza, $pizza)
    {
	    $db = static::getDB();  //recuperiamo un riferimento al database
	   
        $stmt = $db->prepare("UPDATE Pizze SET Nome_pizza = ?, Costo = ?, PesoPizza = ?, Adatta_Celiaci = ?, Adatta_IntolleantiLattosio = ?, Img = ? WHERE Id_Pizza = ?");
        $stmt->bind_param("sdiiisi", $nome_pizza_param, $costo_param, $peso_param, $adatta_Celiaci_param, $adatta_IntolleantiLattosio_param, $img_param, $id_pizza_param);
        
        // set parameters and execute
        $nome_pizza_param = $pizza['Nome_pizza'];
        $costo_param = $pizza['Costo'];
        $peso_param = $pizza['PesoPizza'];
        $adatta_Celiaci_param = $pizza['Adatta_Celiaci'];
        $adatta_IntolleantiLattosio_param = $pizza['Adatta_IntolleantiLattosio'];
        $img_param = $pizza['Img'];
        $id_pizza_param = $id_pizza;
        $stmt->execute();
        if($stmt->error){
            printf("Error: %s.\n", $stmt->error);
        }
        $stmt->close();
	}
	
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
    
    static function getPizza($id_pizza)
    {
	    $db = static::getDB();  //recuperiamo un riferimento al database
        
        $sql = "SELECT * FROM Pizze AS P WHERE P.Id_Pizza = ?"; 
        $stmt = $db->prepare($sql);  //preparo la query
        if($stmt) // controllo se la stmt e' piena (true)
        {
        	$stmt->bind_param("i", $param_idpizza); //assoocio i parametri da input ai ? della query preparata
        	$param_idpizza = $id_pizza; //associo il valore del param al valore passato alla funzione $id_pizza
        	$stmt->execute();
        	$result = $stmt -> get_result();
        	$row = $result->fetch_assoc();
        	if($stmt->error){
                printf("Error: %s.\n", $stmt->error);
            }
        	$stmt->close(); //chiudo la connessione
        }
        return $row;  
    }
    
}
