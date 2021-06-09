

Nel **controller** il file viene letto dal form e caricato nel database:
```PHP
	$target_file = "";
	if(isset($_FILES["pizzaimg"])){
		$target_dir = "/b_utente21/mvc/public/Immagini/";
		$target_file = $target_dir . basename($_FILES["pizzaimg"]["name"]);
	}
	$pizza['Img'] = $target_file;
	$id_pizza = Pizza::addPizza($pizza);   // l'id è generato da mysql
```	

Nel **modello** il file viene successivamente letta, al momento della stampa dell'elenco delle pizze quando
recuperata una riga dal database mediante ```$row = $result->fetch_array(MYSQLI_ASSOC);``` si compone il dato da visualizzare:
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

Nella **vista** viene scritta recuperando il campo del path del file dall'**array associativo** nel template Twig:
```HTML	
	<p><img src="{{ pizza.Img }}" width="500"></p>
```
