>[Torna a modello](model.md) 

```PHP
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
```

```PHP
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
```
>[Torna a modello](model.md) 
