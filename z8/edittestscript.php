<?php declare(strict_types=1); //włączenie typowania zmiennych w PHP >=7
error_reporting(0);
session_start(); //zapewnia dostęp do zmienny sesyjnych w danym pliku
if (!isset($_SESSION['loggedin']))
{
	header('Location: logowanie.php');
	exit();
}
	
		$link = mysqli_connect();
		if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); }		

		$user = $_SESSION['username']; //kto dodaje
		$name = $_POST['name'];
		$description = $_POST['description'];
		
		$idt = $_GET['idt'];
		
		
		if($name != ''){
			$query = mysqli_query($link, "UPDATE test SET name='$name' WHERE idt=$idt");
		}
		if($description != ''){
			$query = mysqli_query($link, "UPDATE test SET description='$description' WHERE idt=$idt");
		}

		mysqli_close($link);
	
	header('Location: index1.php');
?>