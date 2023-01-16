<?php declare(strict_types=1); //włączenie typowania zmiennych w PHP >=7
session_start(); //zapewnia dostęp do zmienny sesyjnych w danym pliku
if (!isset($_SESSION['loggedin']))
{
	header('Location: logowanie.php');
	exit();
}
//dodawanie uzytkownikow do bazy przez admina

$link = mysqli_connect('', '', '', '');
if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); }		

$login = $_POST['login'];
$password = $_POST['password'];
$role = $_POST['role'];

$query = mysqli_query($link, "INSERT INTO user (login, password, role) 
VALUES ('$login', '$password', '$role')");

mysqli_close($link);
header('Location: index1.php');
?>