>[Torna a vista](view.md) 


Nel controller, la action che chiama il form esegue il ciclo 
```PHP
for($i=1; $i<11;$i++){
	$params[$i] = $param.$i;
}
```
che costruisce un array associativo con il nome di ciascun input degli ingredienti. Si è scelto **lato server** di inserire nel form un numero fisso di campi di selezione testo di input, inizialmente nascosti, ma che poi sono visualizzati uno alla volta mediante CSS e javascript eseguito **lato client**.

```HTML

{% extends "base.html" %}

{% block title %}Pizze{% endblock %}

{% block body %}
    <a href='/b_utente21/mvc/public/home/index/'>Torna indietro</a>
    <br>
    <h2>Inserisci pizze</h2>
    <form action="/b_utente21/mvc/public/pizze/do-inseriscipizza/" name="Pizze_Form" method="post" enctype="multipart/form-data">
		<div>
			<label for="nome" >Nome: </label>
			<input name="nome" type="text" id="nome"><br>
			<label for="costo" >Costo: </label>
			<input name="costo" type="text" id="costo"><br>
			<label for="peso" >Peso: </label>
			<input name="peso" type="text" id="peso"><br>
			<label for="celiaci" >E' per celiaci: </label>
			<input type="checkbox" name="celiaci" value="no"/><br/>
			<label for="lattosio" >E' per intolleranti al lattosio: </label>
			<input type="checkbox" name="lattosio" value="no"/><br/>
		        <label for="fileToUpload" >Seleziona l'immagine da caricare: </label>
			<input type="file" name="pizzaimg" id="pizzaimg"><br/>
		</div>
		</br>
	   	{% for param in params %}
			<div name="{{ param }}_div" id="{{ param }}_div" hidden>
				<label for="{{ param }}" >{{ param }}: </label>
				<select name="{{ param }}" id="{{ param }}" >
				{% for ingrediente in ingredienti %} 
					<option value="{{ ingrediente.Id_Ingrediente }}">{{ ingrediente.Nome }}</option>
			    	{% endfor %}
				</select><br>
				<label  for="{{ param }}_quantita" >Quantità: </label >
				<input name="{{ param }}_quantita" type="text" id="{{ param }}_quantita" >
				<input name="{{ param }}_add" type="button" id="{{ param }}_add" value="+" >
			</div>
		{% endfor %}
		<br/>
		<button name="Submit" value="Login"  type="submit">Submit</button>
	</form>
	<script>
	    var i = 0;
	    function plus(){
	        i++;
	        var ing = '{{ base }}';
	        var str = ing+String(i);
	        var str2 = str+"_add";
	        document.getElementById(str+"_div").removeAttribute("hidden");
	        act.removeEventListener('click', plus);
	        act = document.getElementById(str2);
	        act.addEventListener('click', plus, false);
	    }
	    var act = document.getElementById("ingrediente1_add");
	    plus();
	    act.addEventListener('click', plus, false);
	</script>
	<br>
	<a href='/b_utente21/mvc/public/home/index/'>Torna indietro</a>
{% endblock %}


```
>[Torna a vista](view.md) 
