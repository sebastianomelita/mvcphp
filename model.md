>[Torna a MVC](mvcindex.md) 
## **Model**

I modelli che consideriamo noi sono sostanzialmente due: 
-	**accesso al DB** per leggere o scrivere. Serve a recuperare, mediante una query SQL, quelle informazioni che devono essere organizzate in strutture PHP adatte ad una loro visualizzazione in una pagina o a alla composizione di una stringa JSON.
-	**accesso a web service** per filtraggio dei campi JSON e per la loro aggregazione in strutture PHP adatte ad una loro visualizzazione in una pagina o a alla composizione di una stringa JSON.
 

<img src="model.png" width="300">

Occorre precisare che le classi del modello possiedono solo **metodi statici** che, in quanto tali, possono accedere soltanto a **proprietà statiche**. 

I metodi statici sono dichiarati anteponendo il qualificatore **static** davanti il nome del metodo, ad es:
```PHP
static function getHashedPsw($username,&$authlevel){
```
Le **proprietà statiche** sono dichiarate anteponendo il qualificatore ```static``` davanti il nome della proprietà:
```PHP 
private static $result = "";
```
Nonostante ciò le **classi statiche** possono restituire **oggetti dinamici**. L'unica differenza è che vengono chiamate **col nome della classe** piuttosto che con quello dell'oggetto. Gli oggetti **che restituiscono** sono però **dinamici** per cui ad essi si accede come al solito mediante l'operatore -> avente davanti il **nome dell'oggetto**:

Tutti i modelli ereditano da una classe padre l’**accesso al database** che nelle **classi figlie** è recuperabile con la chiamata:

```PHP 
$db = static::getDB();
```
Tutti i modelli ereditano da una classe padre l’**accesso ad un client HTTP** che nelle **classi figlie** è recuperabile con la chiamata:

```PHP 
$rc = static::getRESTClient(); 
```

```PHP 
$result = $db -> query($sql);
```
restituisce un array associativo risultato di una qyery SQL, mentre

```PHP 
$json = $rc->getJSONResponse();
```
restituisce una stringa JSON risultato di una richiesta HTTP.

Dal punto di vista organizzativo, conviene concentrare **la logica** dell'accesso alle **risorse** (database o webservice) **dentro il modello** organizzandola in un **set di funzioni** in grado di restituire tutti i dati necessari al controller per le sue elaborazioni (in genere la visualizzazione). Ogni **funzione** si occuperà di fare una **interrogazione** ben precisa, parametrica o meno, che restituisca un **singolo dato** oppure un **array associativo di dati** da utilizzare nel **controller**.,

Eventuali **eleborazioni sui dati**, ad esempio statistiche come il calcolo di una media, è bene che siano anch'esse svolte **nel modello**. Il modello è quindi il luogo deputato a:
- **recupero dei dati** dal **database** o dalla rete (**webservice**), filtrando le informazioni di interesse
- elaborazione della **logica applicativa** (ad esempio calcolo della contabilità)

**Esempi completi**

- [Modello per gestione accesso ad un DB](esmodeluser.md)
- [Modello per gestione accesso ad un webservice](eswebservice.md)

>[Torna a MVC](mvcindex.md) 
