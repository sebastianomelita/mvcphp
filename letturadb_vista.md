>[Torna a vista](view.md) 

Lettura dell'elenco delle pizze per utenti non registrati che non hanno diritti di modifica o cancellazione.

La lista dei dati è recuperata **dal modello** con ```Pizza::getPizze()``` ed è passata al template nel formato richiesto per il passaggio, cioè  includendola nell'array associativo 
```PHP 
[
    'pizze' => $pizze
]
```
L'array associativo possiede una **struttura ad albero** che combacia con quella del DOM della pagina da visualizzare, in maniera tale che, leggendo nodo per nodo l'array, si riempie, elemento per elemento, il template HTML della pagina:

```HTML
{% extends "base.html" %}

{% block title %}Pizza per nome{% endblock %}

{% block body %}
    <a href='/b_utente21/mvc/public/home/index/'>Torna indietro</a>
    <br>
    <h1>La pizzeria di Alex</h1>
    {% for pizza in pizze %}
        <h2>{{ pizza.Nome_pizza }}</h2>
        <p><img src="{{ pizza.Img }}" width="500"></p>
        <p>Prezzo: {{ pizza.Costo }}</p>
        <p>Ingredienti:</p>
        <ol>
            {% for ingrediente in pizza.ingredienti %}
                <li>{{ ingrediente.Nome }}, Surgelato: {{ ingrediente.SurgelatoStr }}</li>
            {% endfor %}
        </ol>  
    {% endfor %}
    <br>
    <a href='/b_utente21/mvc/public/home/index/'>Torna indietro</a>
{% endblock %}
```
Lettura dell'elenco delle pizze per utenti non registrati che posseggono diritti di modifica o cancellazione:
```HTML
{% extends "base.html" %}

{% block title %}Drinks per nome{% endblock %}

{% block body %}
    <a href='/b_utente21/mvc/public/home/index/'>Torna indietro</a>
    <form action="/b_utente21/mvc/public/pizze/do-cancellapizza/" name="Pizze_cancel" method="post">
        <h1>La pizzeria di Alex</h1>
        {% for pizza in pizze %}
            <a href='/b_utente21/mvc/public/pizze/aggiornapizza/{{ pizza.Id_Pizza }}/'>Modifica pizza</a><br>
            <label for="{{ pizza.Id_Pizza }}" >Seleziona la pizza </label>
            <input name="pizza_{{ pizza.Id_Pizza }}" type="checkbox" id="pizza_{{ pizza.Id_Pizza }}" value="{{ pizza.Id_Pizza }}">
            <br>
            <h2>{{ pizza.Nome_pizza }}</h2>
            <p><img src="{{ pizza.Img }}" width="500"></p>
            <p>Prezzo: {{ pizza.Costo }}</p>
            <p>Ingredienti:</p>
            <ol>
                {% for ingrediente in pizza.ingredienti %}
                    <li>{{ ingrediente.Nome }}, Surgelato: {{ ingrediente.SurgelatoStr }}</li>
                {% endfor %}
            </ol>  
        {% endfor %}
        <br>
        <button name="Cancel" value="Cancel"  type="submit">Cancella selezionati</button>
    </form>
    <a href='/b_utente21/mvc/public/home/index/'>Torna indietro</a>
{% endblock %}
```
Notare che l'**update** è ottenuto con un'ancora che si può trasformare esteticamente in un **pulsante** via CSS. L'ancora richiama la action 
```PHP 
/aggiornapizza/{{ pizza.Id_Pizza }}/ 
``` 
che prende un parametro in formato REST che rappresenta l'id della pizza da modificare.

Le pizze da cancellare si impostano con una selezione multipla via checkbox. Gli id delle pizze da cancellare sono inviati come parametri POST del form. La action di gestione del form li riconosce tramite il prefisso comune 'pizza'.

>[Torna a vista](view.md) 
