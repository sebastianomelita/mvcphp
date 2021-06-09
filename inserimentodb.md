
>[Torna a Controller](controller.md) 

### **Form di inserimento**

I **dati da visualizzare** nella vista devono essere inseriti **tutti nel modello**. 

La **rappresentazione** delle informazioni **nella vista** spesso ha una **struttura ad albero** (ad esempio quella del DOM HTML) che deve essere decostruita suddividendo le informazioni sulle varie tabelle del database relazionale. 

Un **gruppo di informazioni** provenienti da un **form** in una vista possono essere raccolte in un **oggetto** o in un **array associativo** che viene passato come parametro alla funzione del modello che ha il compito di **renderle persistenti** memorizzandole in una certa **tabella** mediante una **query di insermento** (INSERT SQL).

Ad esempio una pizza può essere vista come l'insieme di Nome, costo, proprietà nutrizionali che devono essere memorizzate nella tabella Pizze, piuttosto che in un'altra tabella.

Altra informazioni di dettaglio, magari fornibili in numero variabile, potrebbero invece finire in un'altra tabella collegata a quella principale.

Ad esempio, gli ingredienti di una pizza potrebbero essere in numero arbitrario e, pur essendo collegati ad una certa pizza, devono essere memorizzati in maniera persistente sulla tabella Composizioni attraverso una **seconda query** di inserimento.

**In sostanza**, per memorizzare sul modello le informazioni sugli elementi HTML di un certo livello di un form si deve:
1. **iterare sulla lista dei parametri del form** GET o POST che devono essere memorizzati insieme, inserirli nei campi di un oggetto o di un array associativo che **rappresenta il record principale** da memorizzare (riga o tupla).
2. passare l'array o l'oggetto che rappresentano il record da inserire ad una **funzione del modello** che esegue la **query di inserimento** che restituisce l'id della **chiave primaria** eventualmente impostata come autoincrementante.
3. **iterare sulla lista dei parametri del form** GET o POST che devono essere memorizzati insieme su una tabella collegata alla precedente, inserirli nei campi di un oggetto o di un array associativo che **rappresenta il record secondario** da memorizzare (riga o tupla).
4. la funzione esegue nel modello una **seconda query di inserimento** che, utilizzando come **chiave esterna** l'id del record precedentemente inserito, inserisce, uno ad uno, tutti i record secondari.

Oppure se si sta realizzando un web service ed i parametri provengono da un **oggetto JSON**, normalmente, la stringa JSON viene trasformata in un oggetto PHP con il comando **```json_decode($json_str)```**:
1. l'oggetto json viene potrebbe essere passato alla **funzione del modello** che si occupa della sua memorizzazione in forma persistente  **eseguendo una query di inserimento** che restituisce l'id della **chiave primaria** eventualmente impostata come autoincrementante.
2. se l'oggetto contiene uno o più oggetti figli, iterare sulla lista che li contiene e passare il riferimento di ciascuno alla funzione del modello che si occupa della sua memorizzazione in forma persistente su una tabella collegata alla precedente.
3. la funzione esegue nel modello **una seconda query di inserimento** che, utilizzando come **chiave esterna** l'id del record precedentemente inserito, inserisce, uno ad uno, tutti gli oggetti correlati al record principale.

In realtà, soprattutto nel caso del JSON, le operazioni di composizione delle righe da inserire potevano essere fatte anche tutte nel **modello**.

Esempio di funzione del controller che **inserisce** una Pizza (record principale) e tutti i suoi ingredienti (lista di record secondari):
```PHP
public function doInseriscipizza()
{
	session_start();
	if($_SESSION['level'] == 0 || $_SESSION['level'] == 2) {
	    if(isset($_POST['Submit'])){// Controlla se il form è stato sottomesso
		$pizza = array();
		$pizza['Nome_pizza'] = Login::test_input($_POST['nome']);
		$pizza['Costo'] = Login::test_input($_POST['costo']);
		$pizza['PesoPizza'] = Login::test_input($_POST['peso']);
		$pizza['Adatta_Celiaci'] = (isset($_POST['celiaci'])) ? 1: 0;
		$pizza['Adatta_IntolleantiLattosio'] = (isset($_POST['lattosio'])) ? 1: 0;
		$pizza['Img'] = $target_file2;
		// inserimento record principale
		$id_pizza = Pizza::addPizza($pizza);   // l'id è generato da mysql
		if($id_pizza){ // se la pizza non è già in catalogo
		    $ing = "ingrediente";
		    $ingn = "ingrediente1";
		    $i = 1;
		    $ingredienti = array();
		    //print_r($_POST);
		    while(isset($_POST[$ingn]) && !empty($_POST[$ingn])){
			// generazione del record da inserire in una seconda tabella collegata alla prima
			$ingrediente = [
			    'Id_Ingrediente' => Login::test_input($_POST[$ingn]),       //campo value del select
			    'Quantita' => Login::test_input($_POST[$ingn."_quantita"])  //campo value dell'input
			];
			// inserimento del record secondario collegato mediante chiave esterna (associazione 1:N)
			Pizza::addIngredientePizza($id_pizza, $ingrediente);
			$i++;
			$ingn = $ing.$i;
		    }
		}
		$this->inseriscipizzaAction();
	    }
  	}
}
```
E' possibile inserire fino ad un numero di ingredienti fissato nel form. Tutti gli input degli ingredienti hanno il prefisso comune 'ingrediente' e si distinguono per il numero posto dopo (ad es. ingrediente1, ingrediente2...ingrediente10). Un ciclo esamina gli input con numerazione crescente fino a che questi sono istanziati e non vuoti, altrimnti esce.

Esempio di funzione del controller che **inserisce** un oggeto JSON proveniente da un metodo POST:
```PHP
public function doInseriscimisura()
{
    $payload = file_get_contents('php://input');
    if(!empty($payload)){
	 $obj = json_decode($payload);
	 if($obj->token =="123456789") {
		Value::insMisura($obj);
		echo $payload;
	 }
    }
}
```

>[Torna a Controller](controller.md) 
