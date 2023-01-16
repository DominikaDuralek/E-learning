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
	$link = mysqli_connect('', '', '', '');
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
	//$fname = "files/" . $username . "_" . $testname . "_" . $datetime . ".txt"; //nazwa pliku files/uzytkownik_test
	//$myfile = fopen($fname, "w");
	$htmlfile = '<span style="font-weight:bold;">Wynik użytkownika ' . $username . "<br>Test: " . $testname . "</span>";
	//fwrite($myfile, $ftxt);

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

		//ktore poprawne
		$acorrect = $row['acorrect']; //1 lub 0
		$bcorrect = $row['bcorrect'];
		$ccorrect = $row['ccorrect'];
		$dcorrect = $row['dcorrect'];
		
		//odpowiedzi uzytkownika
		$answera = $_POST[$idpyt . 'a']; //odpowiedź uzytkownika a
		$answerb = $_POST[$idpyt . 'b']; //odpowiedź uzytkownika b
		$answerc = $_POST[$idpyt . 'c']; //odpowiedź uzytkownika c
		$answerd = $_POST[$idpyt . 'd']; //odpowiedź uzytkownika d
		
		//tresci pytan + czy poprawne
		$htmlfile = $htmlfile . '<br><br><span style="font-weight:bold;">Pytanie: ' . $text . "</span>";

		//czerwony - #FF4E4E
		//zielony - #5ED868
		
		//a
		if($acorrect){
			$htmlfile = $htmlfile . '<span style="color:#5ED868;"><br>a: ' . $answer_a . "</span>";
		}
		else if(!$acorrect && $answera == 1){ //jesli uzytkownik zaznaczyl zla odp
			$htmlfile = $htmlfile . '<span style="color:#FF4E4E;"><br>a: ' . $answer_a . "</span>";	
		}
		else{
			$htmlfile = $htmlfile . "<br>a: " . $answer_a;		
		}	

		//b
		if($bcorrect){
			$htmlfile = $htmlfile . '<span style="color:#5ED868;"><br>b: ' . $answer_b . "</span>";
		}
		else if(!$bcorrect && $answerb == 1){ //jesli uzytkownik zaznaczyl zla odp
			$htmlfile = $htmlfile . '<span style="color:#FF4E4E;"><br>b: ' . $answer_b . "</span>";	
		}
		else{
			$htmlfile = $htmlfile . "<br>b: " . $answer_b;		
		}

		//c
		if($ccorrect){
			$htmlfile = $htmlfile . '<span style="color:#5ED868;"><br>c: ' . $answer_c . "</span>";
		}
		else if(!$ccorrect && $answerc == 1){ //jesli uzytkownik zaznaczyl zla odp
			$htmlfile = $htmlfile . '<span style="color:#FF4E4E;"><br>c: ' . $answer_c . "</span>";	
		}
		else{
			$htmlfile = $htmlfile . "<br>c: " . $answer_c;		
		}	

		//d
		if($dcorrect){
			$htmlfile = $htmlfile . '<span style="color:#5ED868;"><br>d: ' . $answer_d . "</span>";
		}
		else if(!$dcorrect && $answerd == 1){ //jesli uzytkownik zaznaczyl zla odp
			$htmlfile = $htmlfile . '<span style="color:#FF4E4E;"><br>d: ' . $answer_d . "</span>";	
		}
		else{
			$htmlfile = $htmlfile . "<br>d: " . $answer_d;		
		}		
		
			
		//zbior poprawnych i udzielonych odpowiedzi
		if($answera == $acorrect && $answerb == $bcorrect && $answerc == $ccorrect && $answerd == $dcorrect){ //odp poprawne
			$correct_answers = $correct_answers + 1;
				$htmlfile = $htmlfile . '<span style=""><br><br>Odpowiedziano poprawnie<br>Poprawne odpowiedzi: ';
				//fwrite($myfile, $ftxt);
				
				if($acorrect){
					$htmlfile = $htmlfile . "a ";
					//fwrite($myfile, $ftxt);
				}
				if($bcorrect){
					$htmlfile = $htmlfile . "b ";
					//fwrite($myfile, $ftxt);
				}
				if($ccorrect){
					$htmlfile = $htmlfile . "c ";
					//fwrite($myfile, $ftxt);
				}
				if($dcorrect){
					$htmlfile = $htmlfile . "d ";
					//fwrite($myfile, $ftxt);
				}
				
				$htmlfile = $htmlfile . "<br>Odpowiedzi użytkownika: ";
				//fwrite($myfile, $ftxt);
				
				if($answera == '1'){
					$htmlfile = $htmlfile . "a ";
					//fwrite($myfile, $ftxt);
				}
				if($answerb == '1'){
					$htmlfile = $htmlfile . "b ";
					//fwrite($myfile, $ftxt);
				}
				if($answerc == '1'){
					$htmlfile = $htmlfile . "c ";
					//fwrite($myfile, $ftxt);
				}
				if($answerd == '1'){
					$htmlfile = $htmlfile . "d ";
					//fwrite($myfile, $ftxt);
				}
				$htmlfile = $htmlfile . '</span>';
		}
		else{
			if($answer != ''){	
				$htmlfile = $htmlfile . '<span style=""><br><br>Odpowiedziano niepoprawnie<br>Poprawne odpowiedzi: ';
				//fwrite($myfile, $ftxt);
				
				if($acorrect){
					$htmlfile = $htmlfile . "a ";
					//fwrite($myfile, $ftxt);
				}
				if($bcorrect){
					$htmlfile = $htmlfile . "b ";
					//fwrite($myfile, $ftxt);
				}
				if($ccorrect){
					$htmlfile = $htmlfile . "c ";
					//fwrite($myfile, $ftxt);
				}
				if($dcorrect){
					$htmlfile = $htmlfile . "d ";
					//fwrite($myfile, $ftxt);
				}
				
				$htmlfile = $htmlfile . "<br>Odpowiedzi użytkownika: ";
				//fwrite($myfile, $ftxt);
				
				if($answera == '1'){
					$htmlfile = $htmlfile . "a ";
					//fwrite($myfile, $ftxt);
				}
				if($answerb == '1'){
					$htmlfile = $htmlfile . "b ";
					//fwrite($myfile, $ftxt);
				}
				if($answerc == '1'){
					$htmlfile = $htmlfile . "c ";
					//fwrite($myfile, $ftxt);
				}
				if($answerd == '1'){
					$htmlfile = $htmlfile . "d ";
					//fwrite($myfile, $ftxt);
				}
				$htmlfile = $htmlfile . '</span>';
				
			}
			else{
				$htmlfile = $htmlfile . '<span style=""><br><br>Odpowiedziano niepoprawnie<br>Poprawne odpowiedzi: ';
				//fwrite($myfile, $ftxt);
				
				if($acorrect){
					$htmlfile = $htmlfile . "a ";
					//fwrite($myfile, $ftxt);
				}
				if($bcorrect){
					$htmlfile = $htmlfile . "b ";
					//fwrite($myfile, $ftxt);
				}
				if($ccorrect){
					$htmlfile = $htmlfile . "c ";
					//fwrite($myfile, $ftxt);
				}
				if($dcorrect){
					$htmlfile = $htmlfile . "d ";
					//fwrite($myfile, $ftxt);
				}
				
				$htmlfile = $htmlfile . "<br>Odpowiedzi użytkownika: ";
				//fwrite($myfile, $ftxt);
				
				if($answera == '1'){
					$htmlfile = $htmlfile . "a ";
					//fwrite($myfile, $ftxt);
				}
				if($answerb == '1'){
					$htmlfile = $htmlfile . "b ";
					//fwrite($myfile, $ftxt);
				}
				if($answerc == '1'){
					$htmlfile = $htmlfile . "c ";
					//fwrite($myfile, $ftxt);
				}
				if($answerd == '1'){
					$htmlfile = $htmlfile . "d ";
					//fwrite($myfile, $ftxt);
				}
				$htmlfile = $htmlfile . '</span>';
			}

		}
	}

	$points = $correct_answers . "/" . $num_questions;

	$query = mysqli_query($link, "INSERT INTO result (idu, idt, datetime, points, pdf_file) 
	VALUES ('$idu', '$idt', '$datetime', '$points', '$fname')");

	mysqli_close($link); 

	//header('Location: index1.php');
	
	$htmlfile = $htmlfile . "<br><br>Wynik: " . $points . "p";
	//fwrite($myfile, $ftxt);
	//fclose($myfile);
	
	$_SESSION['htmlfile'] = $htmlfile;

	echo "<a href='generatepdf.php?username=$username&testname=$testname&datetime=$datetime' target='_blank'>Generuj plik</a>";
	echo "<br><a href='index1.php'>Zapisz i zakończ</a>";
?>

</BODY>
</HTML>
