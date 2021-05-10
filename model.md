>[Torna a MVC](mvcindex.md) 
## **Model**

I modelli che consideriamo noi sono sostanzialmente due: 
-	**accesso al DB** per leggere o scrivere. Serve a recuperare, mediante una query SQL, quelle informazioni che devono essere organizzate in strutture PHP adatte ad una loro visualizzazione in una pagina o a alla composizione di una stringa JSON.
-	**accesso a web service** multipli per filtraggio dei campi JSON e per la loro aggregazione in strutture PHP adatte ad una loro visualizzazione in una pagina o a alla composizione di una stringa JSON.
 
![model](model.png)


Tutti i modelli ereditano da una classe padre l’accesso al database che nelle **classi figlie** è recuperabile con la chiamata:
```PHP 
$db = static::getDB();
```
Occorre precisare che le classi del modello sono **tutte statiche** nel senso che possiedono **metodi statici** e **proprietà statiche**. I metodi statici sono dichiarati anteponendo il qualificatore **static** davanti il nome del metodo, ad es:
```PHP
static function getHashedPsw($username,&$authlevel){
```
Le **proprietà statiche** sono dichiarate anteponendo il qualificatore ```static``` davanti il nome della proprietà:
```PHP 
private static $result = "";
```

Conviene concentrare **la logica** dell'accesso alle **risorse** (database o webservice) **dentro il modello** organizzandola in un **set di funzioni** in grado di restituire tutti i dati necessari al controller per le sue leaborazioni (in genere la visualizzazione). Ogni **funzione** si occuperà di fare una **interrogazione** ben precisa, parametrica o meno, che restituisce un **singolo dato** oppure un **array associativo di dati** da utilizzare nel **controller**.

**Esempi completi**

- [Modello per gestione accesso ad un DB](esmodeluser.md)
- [Modello per gestione accesso ad un webservice](eswebservice.md)

>[Torna a MVC](mvcindex.md) 
