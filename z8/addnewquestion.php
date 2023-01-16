<?php declare(strict_types=1); //włączenie typowania zmiennych w PHP >=7
error_reporting(0);
session_start(); //zapewnia dostęp do zmienny sesyjnych w danym pliku
if (!isset($_SESSION['loggedin']))
{
	header('Location: logowanie.php');
	exit();
}
//dodanie pytania do bazy	
		$link = mysqli_connect('', '', '', '');
		if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); }		
		
		$idt = $_POST['idt']; //w ktorym tescie
		$text = $_POST['question'];
		$answer_a = $_POST['answer_a'];
		$answer_b = $_POST['answer_b'];
		$answer_c = $_POST['answer_c'];
		$answer_d = $_POST['answer_d'];
		
		$acorrect = $_POST['apop'];
		$bcorrect = $_POST['bpop'];
		$ccorrect = $_POST['cpop'];
		$dcorrect = $_POST['dpop'];
		
		$datetime = date('Y-m-d H:i:s');
		
		$user_dir = "files/"; //katalog z plikami
		
		$file_name = $_FILES["uploaded_file"]["name"];
		$file_extension = pathinfo($_FILES["uploaded_file"]["name"], PATHINFO_EXTENSION); //rozszerzenie pliku
		if(file_exists($_FILES['uploaded_file']['tmp_name'])){$file_target_location = $user_dir . $file_name;}
		else{$file_target_location = "";}
		move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], $file_target_location);
		
		$query = mysqli_query($link, "INSERT INTO question (idt, text, answer_a, answer_b, answer_c, answer_d, acorrect, bcorrect, ccorrect, dcorrect, file_name, file_extension) 
		VALUES ('$idt', '$text', '$answer_a', '$answer_b', '$answer_c', '$answer_d', '$acorrect', '$bcorrect', '$ccorrect', '$dcorrect', '$file_target_location', '$file_extension')");
		
		mysqli_close($link);
		
		$testname = $_POST['testname'];
	
	header("Location: edittest.php?testname=$testname");
?>