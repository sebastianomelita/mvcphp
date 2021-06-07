
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
