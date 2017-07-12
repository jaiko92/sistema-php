<?php 
	require 'claseConexion.php';

	function validaVacio($valor){
		if(trim($valor) == ''){
	  		return false;
		}else{
	  		return true;
		}
	}

	function validaRequerido($valor){
		if(trim($valor) == '' && !ctype_alpha($valor)){
	  		return false;
		}else{
	  		return true;
		}
	}

	function validaCorreo($valor){
		if(filter_var($valor, FILTER_VALIDATE_EMAIL) == FALSE){
			return false;
		}else{
			return true;
		}
	}

 ?>