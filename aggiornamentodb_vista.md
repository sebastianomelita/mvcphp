
>[Torna a vista](view.md) 

Esempio di vista che realizza il form di aggiornamento di valori e ingredienti di una pizza inviando parametri attraverso il metodo POST. Il form mostra all'utente i campi value preimpostati con i vecchi valori recuperati dal database che vengono rimandati, eventualmente modificati, come parametri POST del form.

Il campo Id_Pizza, è stato aggiunto dinamicamente lato server come campo nascosto. Non viene visualizzato all'utente ma è trasferito come un ulteriore parametro del form. Sarà utilizzato dalla action che serve il form per effettuare l'aggiornamento del record con quell'id utilizzando i valori POST del form. Si tratta di un metodo alternativo alle sessioni per mantenere una informazione di stato tra una richiesta HTTP e l'altra.

```HTML
	<input type="text" name="id_pizza" id="id_pizza" value="{{ pizza.Id_Pizza }}" hidden>
```
Di seguito è riportato il form completo:

```HTML
{% extends "base.html" %}

{% block title %}Pizze{% endblock %}

{% block body %}
    <a href='/b_utente21/mvc/public/pizze/listapizze/'>Torna indietro</a>
    <br>
    <h2>Modifica pizza</h2>
    <form action="/b_utente21/mvc/public/pizze/do-aggiornapizza/" name="Pizze_Form" method="post" enctype="multipart/form-data">
		<div>
			<label for="nome" >Nome: </label>
			<input name="nome" type="text" id="nome" value="{{ pizza.Nome_pizza }}"><br>
			<label for="costo" >Costo: </label>
			<input name="costo" type="text" id="costo" value="{{ pizza.Costo }}"><br>
			<label for="peso" >Peso: </label>
			<input name="peso" type="text" id="peso" value="{{ pizza.PesoPizza }}"><br>
			<label for="celiaci" >E' per celiaci: </label>
			<input type="checkbox" name="celiaci" value="{{ pizza.Adatta_Celiaci }}"/><br/>
			<label for="lattosio" >E' per intolleranti al lattosio: </label>
			<input type="checkbox" name="lattosio" value="{{ pizza.Adatta_IntolleantiLattosio }}"/><br/>
		        <label for="fileToUpload" >Seleziona l'immagine da caricare: </label>
			<input type="file" name="pizzaimg" id="pizzaimg" value="{{ pizza.Img }}"><br/>
			<input type="text" name="id_pizza" id="id_pizza" value="{{ pizza.Id_Pizza }}" hidden>
			<input type="text" name="img" id="img" value="{{ pizza.Img }}" hidden>
		</div>
		</br>
		<div>
	        {% for param in params %}
			<div name="{{ param.value }}_div" id="{{ param.value }}_div" hidden>
				<label for="{{ param.value }}" >{{ param.value }}: </label>
				<select name="{{ param.value }}" id="{{ param.value }}" >
				{% for ingrediente in param.ingredienti %} 
				    <option value="{{ ingrediente.Id_Ingrediente }}" {{ ingrediente.Checked }}>{{ ingrediente.Nome }}</option>
				{% endfor %}
			</select><br>
			<label  for="{{ param.value }}_quantita" >Quantità: </label >
			<input name="{{ param.value }}_quantita" type="text" id="{{ param.value }}_quantita" value="{{ param.quantita }}">
			<input name="{{ param.value }}_add" type="button" id="{{ param.value }}_add" value="+" >
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
	        var str3 = str+"_quantita";
	        document.getElementById(str+"_div").removeAttribute("hidden");
	        act.removeEventListener('click', plus);
	        act = document.getElementById(str2);
	        act.addEventListener('click', plus, false);
	        return document.getElementById(str3).value > 0; 
	    }
	    var act = document.getElementById("ingrediente1_add");
	    while(plus());
	    act.addEventListener('click', plus, false);
	</script>
	<br>
	<a href='/b_utente21/mvc/public/pizze/listapizze/'>Torna indietro</a>
{% endblock %}

```
Inizialmente il listener è sul select numero 1 ma poi, ad ogni pressione del pulsante '+' dell'ultimo select, viene reso visibile il select successivo e l'evento lettura pressione pulsante viene trasferito su di esso.
>[Torna a vista](view.md) 
