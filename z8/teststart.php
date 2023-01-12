<?php declare(strict_types=1); //włączenie typowania zmiennych w PHP >=7
session_start(); //zapewnia dostęp do zmienny sesyjnych w danym pliku
if (!isset($_SESSION['loggedin']))
{
	header('Location: logowanie.php');
	exit();
}
//rozwiazywanie testu
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
	<div class="topbar">
		<p class='toptext'>PLATFORMA E-LEARNINGOWA</p>
	</div>
	
	<div class="content">
	
		<div class="sidecontent"> 
			<?php
				error_reporting(0);
				$link = mysqli_connect(); //połączenie z BD
				if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } //obsługa błędu połączenia z BD
				date_default_timezone_set('Europe/Warsaw');
				
				$datetime = date('Y-m-d H:i:s');
				
				//informacja o tym kto jest zalogowany
				$username = $_SESSION['username'];
				echo "Zalogowano - " . $_SESSION['username'];
				
				echo "<br>----------------------------------------";
				date_default_timezone_set('Europe/Warsaw');
					
				//informacja o ostatniej probie wlamania sie na konto
				$breakins = mysqli_query($link, "SELECT * FROM break_ins WHERE username='$username' ORDER BY datetime DESC LIMIT 1");
				foreach ($breakins as $row) {
					if($row['datetime'] != ""){
						echo "<br><p style='color: black';>Ostatnia próba włamania:<br>DATA: " . $row['datetime'] . "<br>IP: " . $row['ip'] . "</p>";			
					}
				}
				
				echo "<br><a href='logout.php'>Wyloguj się</a>";
				
				//pobieranie roli uzytkownika
				$roleQuery = mysqli_query($link, "SELECT * FROM user WHERE login='$username'");
				foreach ($roleQuery as $row) {
					$role = $row['role'];
				}
				
				//link do strony głównej
				echo "<br>----------------------------------------";
				echo "<br><a href='index1.php'>Strona główna</a>";
				
				//dodawanie nowych uzytkownikow
				if($role == 'admin'){
					echo "<br>----------------------------------------";
					echo "<br><a href='newuser.php'>Dodaj Coacha/Pracownika</a>";
				}
				
				//lista lekcji dostepnych na portalu
				echo "<br>----------------------------------------";
				echo "<br>Lekcje:";
				$lessons = mysqli_query($link, "SELECT * FROM lesson");
				foreach ($lessons as $row) {
					$lessonname = $row['name'];
					echo "<br><a href='lessonpage.php?lessonname=$lessonname'>• " . $lessonname . "</a>";
				}
				
				//lista testow dostepnych na portalu
				echo "<br>----------------------------------------";
				echo "<br>Testy:";
				$tests = mysqli_query($link, "SELECT * FROM test");
				foreach ($tests as $row) {
					$testname = $row['name'];
					echo "<br><a href='testpage.php?testname=$testname'>• " . $testname . "</a>";
				}
				
				//coach ma wglad do pracownikow
				if($role == 'coach'){
					echo "<br>----------------------------------------";
					echo "<br>Pracownicy:";
					$pracownikquery = mysqli_query($link, "SELECT * FROM user WHERE role='pracownik'");
					foreach ($pracownikquery as $row) {
						$pracowniklogin = $row['login'];
						echo "<br><a href='pracownikpage.php?pracowniklogin=$pracowniklogin'>• " . $pracowniklogin . "</a>";
					}
				}				
				
			?>
			
		</div>
		
		<div class="maincontent">	
			<?php
				$idt = $_GET['idt'];
				
				//pobranie danych o tescie
				$testquery = mysqli_query($link, "SELECT * FROM test WHERE idt='$idt'"); //pobranie odpowiedniego testu
				foreach ($testquery as $row) {
					$testname = $row['name']; //nazwa testu
					$max_time = $row['max_time']; //czas testu
					$authorid = $row['idu'];
				}
				
				//dodanie aktywnosci do logow:
				//pobranie id zalogowanego uzytkownika
				if($role == 'pracownik'){
				$userquery = mysqli_query($link, "SELECT * FROM user WHERE login='$username'");
				foreach ($userquery as $row) {
					$idu = $row['idu'];
				}
				$activity = 'Uruchomienie testu - ' . $testname;
				$query = mysqli_query($link, "INSERT INTO log (idu, datetime, activity) 
				VALUES ('$idu', '$datetime', '$activity')");
				}
				
				//pobranie id autora testu
				$authornamequery = mysqli_query($link, "SELECT * FROM user WHERE idu='$authorid'"); //pobranie odpowiedniego coacha
				foreach ($authornamequery as $row) {
					$author = $row['login']; //nazwa autora
				}
				
				echo "Rozwiązywanie testu: " . $testname . "<br>" ;
				echo "(autor: " . $author . ")<br>";
				
				
				//echo "<br>Czas: " .  $max_time;
				
				//pytania do wypelnienia
				echo "<br><br>Pytania:";
				
				echo "<form action='testfinish.php' method='POST'>";
				echo "<input type='hidden' name='idt' id='idt' value='$idt'>";
				
				//pobranie pytan z bazy
				$questions = mysqli_query($link, "SELECT * FROM question WHERE idt='$idt'"); //pobranie odpowiednich pytan
				foreach ($questions as $row) {
					echo "<br>-----------------------------------------------------------------------";
					echo "<br>" . $row['text'];
					
					$idpyt = $row['idpyt'];
					
					echo"
					<br>
					<label for='a'>
						<input type='radio' id='a' name='$idpyt' value='a'>
						a) " . $row['answer_a'] . "</label>
					<br>
					<label for='b'>
						<input type='radio' id='b' name='$idpyt' value='b'>
						b) " . $row['answer_b'] . "</label>
					<br>
					<label for='c'>
						<input type='radio' id='c' name='$idpyt' value='c'>
						c) " . $row['answer_c'] . "</label>
					<br>
					<label for='d'>
						<input type='radio' id='d' name='$idpyt' value='d'>
						d) " . $row['answer_d'] . "</label>
					<br>";
				
				}
				
				echo "<br><br><input type='submit' value='Zakończ'>";
				
				echo "</form>";
			?>
		</div>
		
	</div>
</BODY>
</HTML>

<?php mysqli_close($link); ?>