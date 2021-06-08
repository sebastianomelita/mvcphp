>[Torna a Controller](controller.md) 

### **Form di aggiornamento**

I **dati da visualizzare** nella vista devono essere inseriti **tutti nel modello**. 

La **rappresentazione** delle informazioni **nella vista** spesso ha una **struttura ad albero** (ad esempio quella del DOM HTML) che deve essere decostruita suddividendo le informazioni sulle varie tabelle del database relazionale. 

Un **gruppo di informazioni** provenienti da un **form** in una vista possono essere raccolte in un **oggetto** o in un **array associativo** che viene passato come parametro alla funzione del modello che ha il compito di **renderle persistenti** memorizzandole in una certa **tabella** mediante una **query di insermento** (INSERT SQL).

Ad esempio una pizza può essere vista come l'insieme di Nome, costo, proprietà nutrizionali che devono essere memorizzate nella tabella Pizze, piuttosto che in un'altra tabella.

Altra informazioni di dettaglio, magari fornibili in numero variabile, potrebbero invece finire in un'altra tabella collegata a quella principale.

Ad esempio, gli ingredienti di una pizza potrebbero essere in numero arbitrario e, pur essendo collegati ad una certa pizza, devono essere memorizzati in maniera persistente sulla tabella Composizioni attraverso una **seconda query** di inserimento.

**In sostanza**, per memorizzare sul modello le informazioni sugli elementi HTML di un certo livello di un form si deve:
1. **iterare sulla lista dei parametri del form** GET o POST che devono essere memorizzati insieme, inserirli nei campi di un oggetto o di un array associativo che **rappresenta il record principale** da memorizzare (riga o tupla).
2. passare l'array o l'oggetto che rappresentano il record da inserire ad una **funzione del modello** che esegue la **query di aggiornamento** che restituisce l'id della **chiave primaria** eventualmente impostata come autoincrementante.
3. **iterare sulla lista dei parametri del form** GET o POST che devono essere memorizzati insieme su una tabella collegata alla precedente, inserirli nei campi di un oggetto o di un array associativo che **rappresenta il record secondario** da memorizzare (riga o tupla).
4. la funzione esegue nel modello una **seconda query di aggiornamento** che, utilizzando come **chiave esterna** l'id del record precedentemente inserito, inserisce, uno ad uno, tutti i record secondari.

Oppure se si sta realizzando un web service ed i parametri provengono da un **oggetto JSON**, normalmente, la stringa JSON viene trasformata in un oggetto PHP con il comando **```json_decode($json_str)```**:
1. l'oggetto json viene potrebbe essere passato alla **funzione del modello** che si occupa della sua memorizzazione in forma persistente  **eseguendo una query di aggiornamento** che restituisce l'id della **chiave primaria** eventualmente impostata come autoincrementante.
2. se l'oggetto contiene uno o più oggetti figli, iterare sulla lista che li contiene e passare il riferimento di ciascuno alla funzione del modello che si occupa della sua memorizzazione in forma persistente su una tabella collegata alla precedente.
3. la funzione esegue nel modello **una seconda query di aggiornamento** che, utilizzando come **chiave esterna** l'id del record precedentemente inserito, inserisce, uno ad uno, tutti gli oggetti correlati al record principale.

In realtà, soprattutto nel caso del JSON, le operazioni di composizione delle righe da inserire potevano essere fatte anche tutte nel **modello**.

Esempio di funzione del controller che **carica il form di aggiornamento** dei valori di una Pizza e di tutti i suoi ingredienti:
```PHP
public function aggiornapizzaAction(){
	$id_pizza = $this->route_params['id'];

	$pizza = Pizza::getPizza($id_pizza);
	$param = "ingrediente";
	$params =  Pizza::getIngredientickecked($id_pizza,$param,10);

	$path = 'Pizza/form_pizza_update.html';
	View::renderTemplate($path, [
	    	'pizza' => $pizza,
		'params' => $params,
		'base' => $param
	]); 
}
```
La funzione **```getIngredientickecked()```** del modello Pizza restituisce **l'albero completo** dei valori degli elementi HTML relativi agli ingredienti **da modificare**, già pronto per essere **iterato** con i cicli for nella vista:

```PHP
[[value, quantita, 'ingredienti'=>[[Id_Ingrediente, Nome, Surgelato, SurgelatoStr, Checked], [...]], [...]] 

```

Esempio di funzione del controller che **aggiorna** valori e ingredienti di una pizza con i parametri proveniente dal metodo POST di un form.
Il form aveva mostrato all'utente i campi value preimpostati con i vecchi valori recuperati dal database che vengono rimandati, eventualmente modificati, come parametri POST del form. 

Il campo Id_Pizza, è stato aggiunto dinamicamente lato server come campo nascosto. Non viene visualizzato all'utente ma è trasferito come un ulteriore parametro del form. Sarà utilizzato dalla action che serve il form per effettuare l'aggiornamento del record con quell'id utilizzando i valori POST del form. Si tratta di un metodo alternativo alle sessioni per mantenere una informazione di stato tra una richiesta HTTP e l'altra.

```HTML
	<input type="text" name="id_pizza" id="id_pizza" value="{{ pizza.Id_Pizza }}" hidden>
```
```PHP
public function doAggiornapizza()
{
        session_start();
        if($_SESSION['level'] == 0 || $_SESSION['level'] == 2) {
            if(isset($_POST['Submit'])){// Controlla se il form è stato sottomesso
                $id_pizza = Login::test_input($_POST['id_pizza']);  // l'id o arriva da un campo hidden o da una sessione
                $img = Login::test_input($_POST['img']);
                $pizza = array();
                $pizza['Nome_pizza'] = Login::test_input($_POST['nome']);
                $pizza['Costo'] = Login::test_input($_POST['costo']);
                $pizza['PesoPizza'] = Login::test_input($_POST['peso']);
                $pizza['Adatta_Celiaci'] = (isset($_POST['celiaci'])) ? 1: 0;
                $pizza['Adatta_IntolleantiLattosio'] = (isset($_POST['lattosio'])) ? 1: 0;
                $pizza['Img'] = $target_file2;
                if($pizza['Img']==$target_dir2){
                    $pizza['Img'] = $img;
                }
                Pizza::updatePizza($id_pizza, $pizza);
                $ing = "ingrediente";
                $ingn = "ingrediente1";
                $i = 1;
                $ingredienti = array();
                //print_r($_POST);
                Pizza::removeIngredientiPizza($id_pizza);
                while(isset($_POST[$ingn]) && !empty($_POST[$ingn])){
                    $ingrediente = [
                        'Id_Ingrediente' => Login::test_input($_POST[$ingn]),       //campo value del select
                        'Quantita' => Login::test_input($_POST[$ingn."_quantita"])  //campo value dell'input
                    ];
                    //print_r($ingrediente);
                    Pizza::addIngredientePizza($id_pizza, $ingrediente);
                    $i++;
                    $ingn = $ing.$i;
                }
                //$this->route_params['id'] = $id_pizza;
                $this->listapizzeAction();
            }
        }
}
```

In realtà nel'esempio, per semplificare le operazioni, si è scelto di non eseguire l'aggiornamento selettivo dei soli ingredienti modificati ma di procedere, in maniera più drastica, alla loro cancellazione prima di reinserire quelli contenuti nel form.

Si poteva procedere facendo delle query di aggiornamento applicate a tutti gli ingredienti, cioè sia ai record modificati che a quelli modificati.

Oppure si poteva procedere confrontando i valori ricevuti dal form con quelli corrispondenti originali recuperati dal database e decidere di eseguire l'aggiornamento solo se questi sono diversi.

Oppure si poteva procedere in maniera analoga confrontando i valori ricevuti dal form con quelli corrispondenti originali conservati in  cache in memoria ottenuta, per esempio, salvandoli, al caricamento del form da sottomettere, in una variabile di sessione. A quel punto si può decidere di eseguire l'aggiornamento solo se quelli in cache e quelli modificati dall'utente sono diversi.

>[Torna a Controller](controller.md) 
