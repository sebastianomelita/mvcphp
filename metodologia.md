>[Torna a MVC](mvcindex.md) 

## **PROPOSTA DI UNA METODOLOGIA DI SVILUPPO MVC**

Si propone uno sviluppo a fasi seriale che si adatta alla piattaforma in uso. 
Si può procedere anche diversamente sviluppando modello controller e view sostanzialmente in parallelo. Generalmente ci si divide in due gruppi: uno che gestisce l’implementazione di controller e modello (il programmatore vero e proprio), un altro che gestisce l’implementazione della vista (lo specialista di design web). Chiaramente, nello sviluppo parallelo è necessario concordare convenzioni di sviluppo che permettano una integrazione agevole tra le due parti. Potrebbero essere necessarie: la supervisione di un team leader, momenti di sincronizzazione in cui si controlla il lavoro fatto e si armonizza lo sviluppo.
Ci si propone di suggerire una metodologia adatta, più che altro, ad uno sviluppo seriale da parte di un singolo in maniera seriale.

Suddividiamo il lavoro in fasi. 

Ogni ciclo di fasi può essere incrementale, nel senso che si può applicare sviluppando fino alla fine un set limitato di funzioni coese tra loro, provarle e poi passare allo sviluppo di un altro set di funzioni. Il set di funzioni che conviene sviluppare per primo in maniera completa è quello che riguarda l’autenticazione degli utenti (chi sono) e l’autorizzazione degli utenti (quale risorsa possono vedere) nel sistema. L’autorizzazione può essere gestita a livelli a cui corrisponde un gruppo via via crescente di diritti di accesso alle risorse (diventano più numerose all’aumentare del livello).
Metodologia proposta:
## 1)	**Analisi dei requisiti.** 
Per una applicazione web, dal punto di vista strettamente informatico, prevale l’analisi dei requisiti funzionali. Vanno ricercate tutte quelle operazioni che il sistema deve svolgere per conto dell’attore umano e in collaborazione con esso. L’azione del sistema è complementare a quella dell’utente umano che l’adopera e l’interazione con esso va studiata con cura. Prodotto di questa fase è una descrizione scritta o visuale, grossolana o dettagliata degli use case del sistema da parte degli attori che lo adoperano (generalmente tanti quanti i ruoli aziendali). Come risultato notevole di questa fase c’è lo schema ER del database che guiderà da un lato lo sviluppo dell’autenticazione, dall’altro quello del modello.
## 2)	**Progettazione delle viste.**
Una volta stabiliti i requisiti funzionali si può pensare di progettare un’interazione accessibile e gradevole con cui un’utente può usufruirne. Si tratta di progettare l’interfaccia grafica organizzando il “cruscotto” con cui sono organizzati i punti di accesso al sistema. Una volta sottomessi il tipo di interazione con cui si possono scambiare i dati è generalmente di due tipi:
    -	Richiesta/risposta. Viene inviata una richiesta http in seguito alla quale viene ricevuta dal server una risposta che può essere o un’altra pagina web o una informazione in formato JSON da utilizzare in seno alla pagina aggiornandola (mediante azione del javascript sul DOM della pagina).
    -	Pubblicazione/Sottoscrizione. Sono possibili due ruoli per la pagina di essere un client (spesso assunti insieme):
    -	Publisher. Pubblica informazioni che devono aggiornare lo stato del server in particolare ma di tutti i client subscriber in generale.
    -	Subscriber. Riceve notifica asincrona (in istanti non prevedibili) di dati che possono modificare lo stato o il contenuto delle informazioni visualizzate dalla pagina.
Entrambi i modelli di interazione web possono essere svolti in modalità SPA (Single Page Application) utilizzando plugins Javascript e protocolli opportuni.

L’input della pagina è quindi suddivisibile in zone:
-	Aree in cui si concentra la reportistica dei risultati ottenuti, spesso organizzata in righe successive (tabelle).
-	Aree in cui si concentra l’input di una richiesta http per ottenere una nuova pagina o l’aggiornamento di quella corrente
-	Aree in cui si concentrano gli elementi soggetti ad aggiornamenti a seguito di notifiche asincrone da parte del server, cioè le comunicazioni iniziate dal server (che assume temporaneamente il ruolo di client) e non dalla pagina

La suddivisione in aree e solo in linea di principio perché le varie porzioni potrebbero sovrapporsi.

Le viste possono essere sviluppate in PHP o in con template HTML con il framework TWIG. Il templating permette un riuso semplice delle porzioni immutabili di una pagina web, cioè quelle parti che rimangono invariate tra una pagina web e l’altra. 

Le viste possono essere organizzate in gruppi che gestiscono l’interazione con parti del sistema che svolgono funzioni simili o fortemente correlate. Nel framework in uso sono organizzate in cartelle che raccolgono file con estensione .php o .html. Si propone la convenzione di nominare le cartelle con lo stesso nome del controller che le gestirà oppure con la versione al singolare di quel nome.

## 3)	**Progettazione del modello.** 
Una volta noto lo schema ER dei dati si può passare allo sviluppo del modello logico e alla implementazione delle tabelle sul DBMS. Noto lo schema definitivo delle tabelle relative al blocco funzionale in questione è possibile progettare le query SQL che costituiscono parte del motore funzionale dell’interazione. I modelli sono file php che contengono una singola classe che deriva (per estensione di ereditarietà) dalla classe core del modello. La classe del modello è sempre statica per cui per accedere a proprietà e metodi della medesima classe bisogna usare la notazione self::nome_proprietà o self::nome_metodo. Come approccio pratico si suggerisce di copiare una classe di un modello già esistente e di incollarla sulla cartella del modello da implementare. Successivamente si modifica il vecchio modello iniziando a cambiare il nome, passando poi a modificare le proprietà e per ultimi i metodi.
## 4)	**Progettazione del controller.**
Il controller raccoglie i metodi associati agli eventi generati dall’utente durante la sua interazione col sistema. Sono organizzati per gruppi di funzioni coese, cioè fortemente correlate tra loro. Sono files PHP contenuti in cartelle dentro la cartella Controllers. Possiamo darci la convenzione di nominarli al plurale con lo stesso nome di modello e vista. I controller sono oggetti istanziati dinamicamente al momento dell’arrivo di una richiesta http, per cui per accedere a proprietà e metodi della medesimo controller bisogna usare la notazione $this->nome_proprietà o $this->nome_metodo. Come approccio pratico si suggerisce di copiare una oggetto di un controller già esistente e di incollarlo sulla cartella del controller da implementare. Successivamente si modifica il vecchio controller iniziando a cambiare il nome, passando poi a modificare le proprietà e per ultimi i metodi.Una accortezza particolare bisogna riporre nella modifica delle inclusioni delle librerie dato che un controller potrebbe utilizzare modelli diversi. Un esempio di inclusione è:
use App\Models\Login;

>[Torna a MVC](mvcindex.md) 
