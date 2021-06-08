>[Torna a modello](model.md) 

'''PHP

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
'''
>[Torna a modello](model.md) 
