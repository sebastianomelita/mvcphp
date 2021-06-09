

Nel controller:
```PHP
	$target_file = "";
	if(isset($_FILES["pizzaimg"])){
		$target_dir = "/b_utente21/mvc/public/Immagini/";
		$target_file = $target_dir . basename($_FILES["pizzaimg"]["name"]);
	}
	$pizza['Img'] = $target_file;
```	

Nel modello:
recuperata una riga dal database mediante ```$row = $result->fetch_array(MYSQLI_ASSOC);```:
```PHP
	// composizione array associativo della vista
	$pizza = [
		'Id_Pizza' => $row['Id_Pizza'],
		'Nome_pizza' => $row['Nome_pizza'],
		'Img' => $row['Img'],
		'Costo' => $row['Costo'],
		'ingredienti' => $pingredienti
	];
```	

Nella vista:
```HTML	
	<p><img src="{{ pizza.Img }}" width="500"></p>
```
