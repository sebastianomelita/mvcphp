>[Torna a vista](view.md) 

```HTML
{% extends "base.html" %}

{% block title %}Drinks per nome{% endblock %}

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
>[Torna a vista](view.md) 
