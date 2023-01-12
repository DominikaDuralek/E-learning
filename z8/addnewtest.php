<?php declare(strict_types=1); //włączenie typowania zmiennych w PHP >=7
error_reporting(0);
session_start(); //zapewnia dostęp do zmienny sesyjnych w danym pliku
if (!isset($_SESSION['loggedin']))
{
	header('Location: logowanie.php');
	exit();
}
//dodawanie testu przez coacha		
		$link = mysqli_connect();
		if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); }		

		$user = $_SESSION['username']; //kto dodaje
		
		$name = $_POST['name'];
		$description = $_POST['description'];
		
		//pobranie id coacha, ktory tworzy test
		$coachlist = mysqli_query($link, "SELECT * FROM user WHERE login='$user'");
		foreach ($coachlist as $row) {
			$idu = $row['idu'];
		}		
		
		$testinsert = mysqli_query($link, "INSERT INTO test (idu, name, description) VALUES ('$idu', '$name', '$description')");
		
		mysqli_close($link);
	
	header('Location: index1.php');
?>