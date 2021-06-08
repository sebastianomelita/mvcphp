
>[Torna a Controller](controller.md) 

### **Form di cancellazione**

**Nella vista** un form permette, via spunte su checkbox, la selezione delle pizze da cancellare.  Il nome dei checkbox inizia con un prefisso prestabilito per cui, grazie ad esso, è possibile, **nel controller**, isolarli all'interno della lista dei parametri POST.
```HTML
    <form action="/b_utente21/mvc/public/pizze/do-cancellapizza/" name="Pizze_cancel" method="post">
    <input name="pizza_{{ pizza.Id_Pizza }}" type="checkbox" id="pizza_{{ pizza.Id_Pizza }}" value="{{ pizza.Id_Pizza }}">
```
E' possibile costruire, con il costrutto ```foreach()``` e un ```if()```, un ciclo di selezione che scandisce tutte le celle dell'array associativo $_POST alla ricerca di quelle con il nome che contiene il prefisso 'pizza'. Per ciascuna il contenuto è l'id della pizza da cancellare per cui questo valoe viene passato come argomento del metodo del modello ```Pizza::removePizza($value)``` che provvede a rimuovere la pizza con quell'id. 

Nella tabella è stato impostato in SQL il vincolo di integrità referenziale ```ON DELETE CASCADE``` per cui, alla cancellazione di un record lato ad uno, la pizza, avvien in cascata la cancellazione di tutti i record lato a molti relativi agli ingredienti della pizza cancellata.

```PHP

public function doCancellapizza()
{
    session_start();
    if($_SESSION['level'] == 1 || $_SESSION['level'] == 2) {
        if(isset($_POST['Cancel'])){// Controlla se il form è stato sottomesso
            $pizze = array();
            foreach ($_POST as $key => $value) {
                if(str_contains($key, "pizza")){
                    Pizza::removePizza($value);
                }
            }
        }
    }
    $this->listapizzeAction();
}
```



>[Torna a Controller](controller.md) 
