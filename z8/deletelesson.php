<?php
	//usuwanie lekcji
	session_start(); // zapewnia dostęp do zmienny sesyjnych w danym pliku
	if (!isset($_SESSION['loggedin']))
	{
		header('Location: logowanie.php');
		exit();
	}
	
	$lessonname = $_GET['lessonname']; //id piosenki do usuniecia
	
	$link = mysqli_connect('', '', '', '');
	if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } // obsługa błędu połączenia z BD
	
	$sql = "DELETE FROM lesson WHERE name='$lessonname'"; //usuniecie pliku z bazy
	
	mysqli_query($link, $sql);
	mysqli_close($link);

	header('Location: index1.php');	
?>