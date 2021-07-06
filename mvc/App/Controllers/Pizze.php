<?php

namespace App\Controllers;

use \Core\View;
use App\Models\Pizza;
use App\Models\Login;
use \Core\Error;

/**
 * Home controller
 *
 * PHP version 5.4
 */
class Pizze extends \Core\Controller
{

    private $logged = false;
	/**
     * Before filter
     *
     * @return void
     */
    protected function before()
    {
        //echo "(before) ";
        //return false;
		session_start();
		$logged = false;
		if (isset($_SESSION['username'])) {
		    //echo $_SESSION['level'];
			$logged = true;
		}else{
			//Redirect to login page
			View::renderTemplate('Login/login.html');
		}
		return $logged;
    }

    /**
     * After filter
     *
     * @return void
     */
    protected function after()
    {
        //echo " (after)";
         //echo $_SESSION['level'];
    }

	public function checkImage($fileToUpload, $target_file)
    {
    	$uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        
    	// Check if file already exists
        if (file_exists($target_file)) {
          //echo "Sorry, file already exists.";
          $uploadOk = 0;
        }
        
        // Check file size
        if ($_FILES[$fileToUpload]["size"] > 500000) {
          echo "Sorry, your file is too large.";
          $uploadOk = 0;
        }
        
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
          echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
          $uploadOk = 0;
        }
        
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
          //echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
          if (move_uploaded_file($_FILES[$fileToUpload]["tmp_name"], $target_file)) {
            //echo "The file ". htmlspecialchars( basename( $_FILES[$fileToUpload]["name"])). " has been uploaded.";
          } else {
            echo "Sorry, there was an error uploading your file.";
          }
        }
    }
	
	public function doInseriscipizza()
    {
        session_start();
        if($_SESSION['level'] == 0 || $_SESSION['level'] == 2) {
            if(isset($_POST['Submit'])){// Controlla se il form è stato sottomesso
                $target_file = "";
                if(isset($_FILES["pizzaimg"])){
                    $target_dir = "/var/www/html/b_utente21/mvc/public/Immagini/";
                    $target_dir2 = "/b_utente21/mvc/public/Immagini/";
                    $target_file = $target_dir . basename($_FILES["pizzaimg"]["name"]);
                    $target_file2 = $target_dir2 . basename($_FILES["pizzaimg"]["name"]);
                    $this->checkImage("pizzaimg", $target_file);
                }
                
                $pizza = array();
                $pizza['Nome_pizza'] = Login::test_input($_POST['nome']);
                $pizza['Costo'] = Login::test_input($_POST['costo']);
                $pizza['PesoPizza'] = Login::test_input($_POST['peso']);
                $pizza['Adatta_Celiaci'] = (isset($_POST['celiaci'])) ? 1: 0;
                $pizza['Adatta_IntolleantiLattosio'] = (isset($_POST['lattosio'])) ? 1: 0;
                $pizza['Img'] = $target_file2;
                $id_pizza = Pizza::addPizza($pizza);   // l'id è generato da mysql
                if($id_pizza){ // se la pizza non è già in catalogo
                    $ing = "ingrediente";
                    $ingn = "ingrediente1";
                    $i = 1;
                    $ingredienti = array();
                    //print_r($_POST);
                    while(isset($_POST[$ingn]) && !empty($_POST[$ingn])){
                        $ingrediente = [
                            'Id_Ingrediente' => Login::test_input($_POST[$ingn]),       //campo value del select
                            'Quantita' => Login::test_input($_POST[$ingn."_quantita"])  //campo value dell'input
                        ];
                        Pizza::addIngredientePizza($id_pizza, $ingrediente);
                        $i++;
                        $ingn = $ing.$i;
                    }
                }
                $this->inseriscipizzaAction();
            }
        }
	}
	
	public function doAggiornapizza()
    {
        session_start();
        if($_SESSION['level'] == 0 || $_SESSION['level'] == 2) {
            if(isset($_POST['Submit'])){// Controlla se il form è stato sottomesso
                $target_file = "";
                if(isset($_FILES["pizzaimg"])){
                    $target_dir = "/var/www/html/b_utente21/mvc/public/Immagini/";
                    $target_dir2 = "/b_utente21/mvc/public/Immagini/";
                    $target_file = $target_dir . basename($_FILES["pizzaimg"]["name"]);
                    $target_file2 = $target_dir2 . basename($_FILES["pizzaimg"]["name"]);
                    $this->checkImage("pizzaimg", $target_file);
                }
                $id_pizza = Login::test_input($_POST['id_pizza']);  // l'id o arriva da un campo hidden o da una sessione
                $img = Login::test_input($_POST['img']);
                $pizza = array();
                $pizza['Nome_pizza'] = Login::test_input($_POST['nome']);
                $pizza['Costo'] = Login::test_input($_POST['costo']);
                $pizza['PesoPizza'] = Login::test_input($_POST['peso']);
                $pizza['Adatta_Celiaci'] = (isset($_POST['celiaci'])) ? 1: 0;
                $pizza['Adatta_IntolleantiLattosio'] = (isset($_POST['lattosio'])) ? 1: 0;
                $pizza['Img'] = $target_file2;
                if($pizza['Img']==$target_dir2){
                    $pizza['Img'] = $img;
                }
                Pizza::updatePizza($id_pizza, $pizza);
                $ing = "ingrediente";
                $ingn = "ingrediente1";
                $i = 1;
                $ingredienti = array();
                //print_r($_POST);
                Pizza::removeIngredientiPizza($id_pizza);
                while(isset($_POST[$ingn]) && !empty($_POST[$ingn])){
                    $ingrediente = [
                        'Id_Ingrediente' => Login::test_input($_POST[$ingn]),       //campo value del select
                        'Quantita' => Login::test_input($_POST[$ingn."_quantita"])  //campo value dell'input
                    ];
                    //print_r($ingrediente);
                    Pizza::addIngredientePizza($id_pizza, $ingrediente);
                    $i++;
                    $ingn = $ing.$i;
                }
                //$this->route_params['id'] = $id_pizza;
                $this->listapizzeAction();
            }
        }
	}
	
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
	
	public function doInserisciingrediente()
    {
       session_start();
       if($_SESSION['level'] == 0 || $_SESSION['level'] == 2) {
            if(isset($_POST['submit'])){// Controlla se il form è stato sottomesso
                $ingrediente['nome'] = Login::test_input($_POST['nome']);
                $ingrediente['surgelato'] = (isset($_POST['surgelato'])) ? 1: 0;
                Pizza::addIngrediente($ingrediente);
            }
        }
        $this->inserisciingredienteAction();
	}
	
	public function inseriscipizzaAction(){
	    $param = "ingrediente";
	    $params = array();
	    for($i=1; $i<11;$i++){
	         $params[$i] = $param.$i;
	    }
	    $ingredienti = Pizza::getIngredienti();
	    
	    $path = 'Pizza/form_pizza.html';
	    View::renderTemplate($path, [
                'params' => $params,
                'ingredienti' => $ingredienti,
                'base' => $param
            ]); 
	}
	
	public function aggiornapizzaAction(){
	    $id_pizza = $this->route_params['id'];
	    
	    $pizza = Pizza::getPizza($id_pizza);
	    $param = "ingrediente";
	    $params =  Pizza::getIngredientickecked($id_pizza,$param,10);
	 
	    $path = 'Pizza/form_pizza_update.html';
	    View::renderTemplate($path, [
	            'pizza' => $pizza,
                'params' => $params,
                'base' => $param
            ]); 
	}
	
	public function inserisciingredienteAction(){
	    $path = 'Pizza/form_ingredienti.html';
	    View::renderTemplate($path); 
	}
	
	public function listapizzeAction()
    {
	    $pizze = Pizza::getPizze();
        $path = 'Pizza/listapizze'.$_SESSION['level'].'.html';
        View::renderTemplate($path, [
            'pizze' => $pizze
        ]);  
	}
}
