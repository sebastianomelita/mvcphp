

Nel controller:
```PHP
$target_file = "";
	if(isset($_FILES["pizzaimg"])){
		$target_dir2 = "/b_utente21/mvc/public/Immagini/";
		$target_file2 = $target_dir2 . basename($_FILES["pizzaimg"]["name"]);
	}
	$pizza['Img'] = $target_file2;
```	

Nel modello:
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
