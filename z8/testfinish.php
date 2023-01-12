<?php declare(strict_types=1); //włączenie typowania zmiennych w PHP >=7
error_reporting(0);
session_start(); //zapewnia dostęp do zmienny sesyjnych w danym pliku
if (!isset($_SESSION['loggedin']))
{
	header('Location: logowanie.php');
	exit();
}
//zapisywanie wynikow testu
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="fonts/fontawesome/fontawesome/css/all.css">
	<link rel="stylesheet" href="styles.css">
	<style>
	</style>
</head>
<BODY>

<?php				
	$link = mysqli_connect(); //połączenie z BD
	if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } //obsługa błędu połączenia z BD
	date_default_timezone_set('Europe/Warsaw');
	$datetime = date('Y-m-d H:i:s'); //czas ukonczenia testu

	//porownanie correct z idpyt do wybranej odpowiedzi:

	$username = $_SESSION['username']; //login uzytkownika		
	//pobranie id zalogowanego uzytkownika
	$userquery = mysqli_query($link, "SELECT * FROM user WHERE login='$username'");
	foreach ($userquery as $row) {
		$idu = $row['idu'];
	}
					
	$idt = $_POST['idt']; //id rozwiazanego testu
	
	//pobranie nazwy rozwiazanego testu
	$testquery = mysqli_query($link, "SELECT * FROM test WHERE idt='$idt'");
	foreach ($testquery as $row) {
		$testname = $row['name'];
	}

	$num_questions = 0;
	$correct_answers = 0;
	
	//stworzenie odpowiedniego pliku
	$fname = "files/" . $username . "_" . $testname . "_" . $datetime . ".txt"; //nazwa pliku files/uzytkownik_test
	$myfile = fopen($fname, "w");
	$ftxt = "Wynik użytkownika " . $username . "\nTest: " . $testname;
	fwrite($myfile, $ftxt);

	//pobranie pytan rozwiazywanego testu
	$questions = mysqli_query($link, "SELECT * FROM question WHERE idt='$idt'"); //pobranie odpowiednich pytan
	foreach ($questions as $row) {
		$num_questions = $num_questions + 1; //zliczanie pytan
		
		$idpyt = $row['idpyt'];
		$text = $row['text'];
		$answer_a = $row['answer_a'];
		$answer_b = $row['answer_b'];
		$answer_c = $row['answer_c'];
		$answer_d = $row['answer_d'];
		$correct_answer = $row['correct'];
		
		$ftxt = "\nPytanie: " . $text . "\na: " . $answer_a . "\nb: " . $answer_b . "\nc: " . $answer_c . "\nd: " . $answer_d;
		fwrite($myfile, $ftxt);
		
		$answer = $_POST[$idpyt]; //odpowiedź uzytkownika
		if($answer == $correct_answer){
			$correct_answers = $correct_answers + 1;
			$ftxt = "\nOdpowiedziano poprawnie\nPoprawna odpowiedź: " . $correct_answer . "\nOdpowiedź użytkownika: " . $answer;
		fwrite($myfile, $ftxt);
		}else{
			if($answer != ''){	
				$ftxt = "\nOdpowiedziano niepoprawnie\nPoprawna odpowiedź: " . $correct_answer . "\nOdpowiedź użytkownika: " . $answer;
			}else{
				$ftxt = "\nOdpowiedziano niepoprawnie\nPoprawna odpowiedź: " . $correct_answer . "\nOdpowiedź użytkownika: " . '-';
			}
	fwrite($myfile, $ftxt);
		}
	}

	$points = $correct_answers . "/" . $num_questions;

	$query = mysqli_query($link, "INSERT INTO result (idu, idt, datetime, points, pdf_file) 
	VALUES ('$idu', '$idt', '$datetime', '$points', '$fname')");

	mysqli_close($link); 

	//header('Location: index1.php');
	
	$ftxt = "\nWynik: " . $points . "p";
	fwrite($myfile, $ftxt);
	fclose($myfile);

	echo "<a href='generatepdf.php?username=$username&testname=$testname&datetime=$datetime' target='_blank'>Generuj plik</a>";
	echo "<br><a href='index1.php'>Zapisz i zakończ</a>";
?>

</BODY>
</HTML>
