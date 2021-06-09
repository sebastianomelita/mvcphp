
Dal controller, mediante **inseriscipizzaAction()** viene richiamato dalla vista il form di inserimento dei dati della pizza che, tra gli altri input, contiene quello specifico per realizzare **l'upload di un file**:

```HTML
 <form action="/b_utente21/mvc/public/pizze/do-inseriscipizza/" name="Pizze_Form" method="post" enctype="multipart/form-data">
	<div>
		<label for="nome" >Nome: </label>
		<input name="nome" type="text" id="nome"><br>
		<label for="costo" >Costo: </label>
		<input name="costo" type="text" id="costo"><br>
		<label for="fileToUpload" >Seleziona l'immagine da caricare: </label>
		<input type="file" name="pizzaimg" id="pizzaimg"><br/>
	</div>

```

Il **file dell'immagine** viene caricato in una cartella Immagini, scelta da noi, reso raggiungibile dall'esterno mettendolo all'interno della **cartella public**.

Nel **controller** la funzione seguente controlla il formato del file e lo carica da una directory temporanea alla destinazione definitiva nella **cartella Immagini** mediante l'istruzione **```move_uploaded_file($_FILES[$fileToUpload]["tmp_name"], $target_file)```**:

```PHP
public function loadAndCheckImage($fileToUpload, $target_file)
    {
    	$uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        
    	// Check if file already exists
        if (file_exists($target_file)) {
          //echo "Sorry, file already exists.";
          $uploadOk = 0;
        }
        
        // Check file size
        if ($_FILES[$fileToUpload]["size"] > 500000) {
          echo "Sorry, your file is too large.";
          $uploadOk = 0;
        }
        
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
          echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
          $uploadOk = 0;
        }
        
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
          //echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
          if (move_uploaded_file($_FILES[$fileToUpload]["tmp_name"], $target_file)) {
            //echo "The file ". htmlspecialchars( basename( $_FILES[$fileToUpload]["name"])). " has been uploaded.";
          } else {
            echo "Sorry, there was an error uploading your file.";
          }
        }
    }
```

Nel **controller**, oltre a spostare il **file dell'immagine** nella **cartella di pubblicazione**, il **link del file** viene **caricato nel database**:
```PHP
	$target_file = "";
	if(isset($_FILES["pizzaimg"])){
		$target_dir = "/b_utente21/mvc/public/Immagini/";
		$target_file = $target_dir . basename($_FILES["pizzaimg"]["name"]);
		$this->loadAndCheckImage("pizzaimg", $target_file);
	}
	$pizza['Img'] = $target_file;
	$id_pizza = Pizza::addPizza($pizza);   // l'id è generato da mysql
```	

Nel **modello** il file viene successivamente letto, al momento della **stampa dell'elenco delle pizze**, quando
recuperata una riga dal database mediante **```$row = $result->fetch_array(MYSQLI_ASSOC)``**, si compone il dato da visualizzare:
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
