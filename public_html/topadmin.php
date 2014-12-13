<?php session_start();
@error_reporting(false);
@ini_set("display_errors", false);
/**
	*Incluye variables que serán tomadas por bdconnect para realizar cambios fácilmente 
	*Inicializa la base de datos si el usuario está loggeado (revisar archivo bdconnect) con las variables 
	*especificadas en config/bdvars
**/
include("config/bdvars.php");
include("includes/bdconnect.php");
include("includes/functions.php");
set_time_limit(0);
?>
