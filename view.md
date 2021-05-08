## **Viste**
Le view possono essere di due tipi: 
-	HTML per pagine web tradizionali. Seguono un approccio THIN CLIENT con un minimo carico di elaborazione sul client per quanto riguarda la struttura e la organizzazione dei contenuti in tag HTML.
-	JSON per web app dinamiche lato client. FAT CLIENT con carico di elaborazione sul client sia per quanto riguarda la creazione della struttura dei contenuti in tag HTML che per il rendering della stessa (CSS).

Si prepara un array associativo che contiene tutte le informazioni che devono essere visualizzate, eventualmente annidando in uno o più campi dell’array altri array associativi. Si possono anche preparare più array associativi paralleli.

Le viste possono essere preparate nel modello sostanzialmente in due maniere:
-	PHP puro. In questo caso il codice della pagina HTML viene creato dinamicamente sul server da istruzioni PHP. In particolare si utilizza in maniera più compatta possibile la funzione echo(). La pagina non è più solamente HTML ma composta da codice misto HTL-PHP ed ha estensione .php.
-	Templating. In questo caso si usa un middleware di templating che modifica in maniera quasi trasparente un template statico scritto in codice HTML puro e quindi con estensione .html.

```PHP
Esempio di view “mista”:
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>
<body>
    <h1>Welcome</h1>
    <p>Hello <?php echo htmlspecialchars($name); ?>!</p>
    <ul>
        <?php foreach ($colours as $colour): ?>
            <li><?php echo htmlspecialchars($colour); ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
```
Si noti l’utilizzo dei : per segnalare l’inizio di un blocco di istruzioni PHP come alternativa compatta alle tradizionali parentesi graffe aperte.

Esempio di template html dove si definiscono dei blocchi di template:
```PHP
{% extends "base.html" %}
{% block title %}Portata del giorno{% endblock %
{% block body %}
    <a href='/b_utente21/mvc/public/home/index/'>Torna indietro</a>
    <h1>Il pub di Alex</h1>
    <h2>{{ drinkTitle }}</h2>
    <p><img src="{{ drinkImg }}" width="500"></p>
    <ol>
        {% for drinkIngredient in drinkIngredients %}
            <li>{{ drinkIngredient }}</li>
        {% endfor %}
    </ol>  
    <a href='/b_utente21/mvc/public/home/index/'>Torna indietro</a>
{% endblock %}
```
Nel template precedente viene definito l’html della parte modificata di un template base di cui vengono implicitamente conservate le parti considerate immutabili.

Il template base può avere questa forma:
```PHP
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{% block title %}{% endblock %}</title>
</head>
<body>
    <nav>
        <a href="/b_utente21/mvc/public/logins/login/">Home</a> |
        <a href="/b_utente21/mvc/public/logins/logout/">Logout</a>
    </nav>
    {% block body %}{% endblock %}
</body>
</html> 
{% block title %} e {% block body %} sono una sorta di metatag che indicano al motore di templating dove inserire i blocchi dei vari template componenti all’interno del template base. Ogni blocco si chiude con il corrispondente metatag di chiusura {% endblock %}.
```
