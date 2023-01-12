<?php
	//usuwanie testu
	session_start(); // zapewnia dostęp do zmienny sesyjnych w danym pliku
	if (!isset($_SESSION['loggedin']))
	{
		header('Location: logowanie.php');
		exit();
	}
	
	$idpyt = $_GET['idpyt']; //id pytania do usuniecia
	
	$link = mysqli_connect(); // połączenie z BD
	if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } // obsługa błędu połączenia z BD
	
	$sql = "DELETE FROM question WHERE idpyt='$idpyt'"; //usuniecie pytania z bazy
	
	mysqli_query($link, $sql);
	mysqli_close($link);
	
	$testname = $_GET['testname'];
	header("Location: edittest.php?testname=$testname");
?>