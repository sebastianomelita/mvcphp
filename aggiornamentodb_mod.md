>[Torna a modello](model.md) 

```PHP
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
```
>[Torna a modello](model.md) 


