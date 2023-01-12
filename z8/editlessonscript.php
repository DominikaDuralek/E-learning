<?php declare(strict_types=1); //włączenie typowania zmiennych w PHP >=7
error_reporting(0);
session_start(); //zapewnia dostęp do zmienny sesyjnych w danym pliku
if (!isset($_SESSION['loggedin']))
{
	header('Location: logowanie.php');
	exit();
}
//dodawanie lekcji przez coacha		
		$link = mysqli_connect();
		if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); }		
		//dodanie posta do bazy
		$user = $_SESSION['username']; //kto dodaje
		$name = $_POST['name'];
		$text = $_POST['text'];
		$datetime = date('Y-m-d H:i:s');
		
		$idl = $_GET['idl'];
		
		//pobranie id coacha, ktory tworzy lekcje
		$coachlist = mysqli_query($link, "SELECT * FROM user WHERE login='$user'");
		foreach ($coachlist as $row) {
			$idu = $row['idu'];
		}	
		
		$user_dir = "files/"; //katalog z plikami
		
		$file_name = $_FILES["uploaded_file"]["name"];
		$file_extension = pathinfo($_FILES["uploaded_file"]["name"], PATHINFO_EXTENSION); //rozszerzenie pliku
		if(file_exists($_FILES['uploaded_file']['tmp_name'])){$file_target_location = $user_dir . $file_name;}
		else{$file_target_location = "";}
		move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], $file_target_location);
		
		
		if($name != ''){
			$query = mysqli_query($link, "UPDATE lesson SET name='$name' WHERE idl=$idl");
		}
		if($text != ''){
			$query = mysqli_query($link, "UPDATE lesson SET text='$text' WHERE idl=$idl");
		}
		if(file_exists($_FILES['uploaded_file']['tmp_name'])){
			$user_dir = "files/"; //katalog z plikami
			$file_name = $_FILES["uploaded_file"]["name"];
			$file_extension = pathinfo($_FILES["uploaded_file"]["name"], PATHINFO_EXTENSION); //rozszerzenie pliku
			if(file_exists($_FILES['uploaded_file']['tmp_name'])){$file_target_location = $user_dir . $file_name;}
			else{$file_target_location = "";}
			move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], $file_target_location);
			
			$query = mysqli_query($link, "UPDATE lesson SET file_name='$file_target_location', file_extension='$file_extension' WHERE idl=$idl");
		}
		mysqli_close($link);
	
	header('Location: index1.php');
?>