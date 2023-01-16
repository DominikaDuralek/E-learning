<?php declare(strict_types=1); //włączenie typowania zmiennych w PHP >=7
session_start(); //zapewnia dostęp do zmienny sesyjnych w danym pliku
if (!isset($_SESSION['loggedin']))
{
	header('Location: logowanie.php');
	exit();
}
//strona lekcji
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
				$link = mysqli_connect('', '', '', '');
				if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } //obsługa błędu połączenia z BD
				date_default_timezone_set('Europe/Warsaw');
				
				$datetime = date('Y-m-d H:i:s');
				
				//informacja o tym kto jest zalogowany
				$username = $_SESSION['username'];
				echo "Zalogowano - " . $_SESSION['username'];
				
				echo "<br>----------------------------------------";
				date_default_timezone_set('Europe/Warsaw');
					
				//informacja o ostatniej probie wlamania sie na konto
				$breakins = mysqli_query($link, "SELECT * FROM break_ins WHERE username='$username' ORDER BY datetime DESC LIMIT 1"); // wiersze, w którym login=login z formularza
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
				$lessonname = $_GET['lessonname'];
				echo "Lekcja: " . $lessonname . "<br>";
				
				//dodanie aktywnosci do logow:
				//pobranie id zalogowanego uzytkownika
				if($role == 'pracownik'){
				$userquery = mysqli_query($link, "SELECT * FROM user WHERE login='$username'");
				foreach ($userquery as $row) {
					$idu = $row['idu'];
				}
				$activity = 'Przegląd lekcji - ' . $lessonname;
				$query = mysqli_query($link, "INSERT INTO log (idu, datetime, activity) 
				VALUES ('$idu', '$datetime', '$activity')");
				}
				
				//pobranie id autora lekcji
				$lessonquery = mysqli_query($link, "SELECT * FROM lesson WHERE name='$lessonname'"); //pobranie odpowiedniej lekcji
				foreach ($lessonquery as $row) {
					$authorid = $row['idu']; //id autora
				}
				$authornamequery = mysqli_query($link, "SELECT * FROM user WHERE idu='$authorid'"); //pobranie odpowiedniego coacha
				foreach ($authornamequery as $row) {
					$author = $row['login']; //nazwa autora
				}
				echo "(autor: " . $author . ")<br>";
				
				//zawartosc lekcji
				echo "<div class='lessoncontents'>";
					echo "<div class='lessontext'>";
						//pobranie tekstu lekcji
						foreach ($lessonquery as $row) {
							echo "<br><br>Zawartość:<br>" . $row['text']; //tekst lekcji
						}
					echo "</div>";
					echo "<div class='lessonfile'>";
						//pobranie pliku lekcji
						foreach ($lessonquery as $row) {
							echo "<br><br>Plik:<br>"; //plik lekcji
							$file = $row['file_name'];
							$file_extension = $row['file_extension'];
							if($file!= ""){
								if($file_extension == "png" || $file_extension == "jpg" || $file_extension == "jpeg" || $file_extension == "gif"){
									echo "<img src='$file'><br>";
								}
								if($file_extension == "mp4"){
									echo "<video controls autoplay muted width='320px' height='240px'><source src='$file' type='video/mp4'></video><br>";
								}
								if($file_extension == "mp3"){
									echo "<audio controls><source src='$file' type='audio/mpeg'></audio><br>";
								}
							}else{
								echo "(brak pliku do tej lekcji)";
							}
						}
					echo "</div>";
				echo "</div>";
			?>
		</div>
		
	</div>
</BODY>
</HTML>

<?php mysqli_close($link); ?>