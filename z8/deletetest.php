<?php
	//usuwanie testu
	session_start(); // zapewnia dostęp do zmienny sesyjnych w danym pliku
	if (!isset($_SESSION['loggedin']))
	{
		header('Location: logowanie.php');
		exit();
	}
	
	$testname = $_GET['testname']; //id testu do usuniecia
	
	$link = mysqli_connect('', '', '', '');
	if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } // obsługa błędu połączenia z BD
	
	$sql = "DELETE FROM test WHERE name='$testname'"; //usuniecie pliku z bazy
	
	mysqli_query($link, $sql);
	mysqli_close($link);

	header('Location: index1.php');	
?>