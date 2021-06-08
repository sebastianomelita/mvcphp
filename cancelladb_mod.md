>[Torna a modello](model.md) 

```PHP
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
```

```PHP
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
```
>[Torna a modello](model.md) 

