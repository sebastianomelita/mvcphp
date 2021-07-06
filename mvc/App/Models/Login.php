<?php

namespace App\Models;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Login extends \Core\Model
{

    static	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return$data;
	}
	
	static function getHashedPsw($username,&$authlevel){
		$hashed_password="";
		$authlevel = 0;
		$db = static::getDB();
		$sql = "SELECT id, username, password, authlevel FROM users WHERE username = ?";
		$stmt = $db->prepare($sql);
		if($stmt){
			$stmt->bind_param("s", $param_username);
			$param_username = $username; 
			$stmt->execute();
			$stmt->store_result();
			$nrows = $stmt->num_rows;
			if($nrows == 1){ 
				$stmt->bind_result($id,$username,$hashed_password,$authlevel);	  			
				$stmt->fetch();
			}
			$stmt->close();
		}
		return $hashed_password;
	}	
	
	static function validate_usr(&$err,$username){
		$ok=false;
		$db = static::getDB();
		if(empty($username)){
			$err = "Prego inserire un nome utente.";
		}else{
			$sql = "SELECT id FROM users WHERE username = ?";
			$stmt = $db->prepare($sql);
			if($stmt){
				$stmt->bind_param ("s", $param_username);
				$param_username = $username;
				if($stmt->execute()){
					$stmt->store_result();
					if ($stmt->num_rows == 0){
						$ok = true;
					}else{
						$err = "Questo username  gi in uso."; 
					} 
				}else{
					$err = "Errore non definito. Riprovare pi tardi.";
				}
				$stmt->close();
			}
	   }
	   return $ok;
	}
	
	static function validate_psw(&$err,$password,$conf_password){
		$ok = false;
		
		if(empty($password)){
			$err = "Prego inserire una password.";     
		}elseif(strlen($password) < 6){
			$err = "La password deve avere almeno 6 caratteri.";
		}else{
			if(empty($conf_password)){
				$err = "Prego inserire la conferma della password.";     
			}else{
				if($password == $conf_password){
					$ok = true;
				}else{
					$err = "Le due password non corrispondono.";
				}
			}
		} 
		return $ok;
	}
	
	static function insert_user($username,$password,$authlevel){
	   $ok = false;
	   $db = static::getDB();
	   // Prepare an insert statement
	   $sql = "INSERT INTO users (username, password, authlevel) VALUES (?, ?, ?)";
	   $stmt = $db->prepare($sql);
	   if($stmt){
			$stmt->bind_param("sss", $param_username, $param_password, $param_authlevel);
			// Set parameters
			$param_username = $username;
			$param_password = password_hash($password, PASSWORD_DEFAULT); 
			$param_authlevel = $authlevel;
			// Attempt to execute the prepared statement
			if($stmt->execute()){
				$ok = true;
			}
			$stmt->close();
	   }
	   return $ok;
	}
}