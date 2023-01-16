<?php declare(strict_types=1); //włączenie typowania zmiennych w PHP >=7
session_start(); //zapewnia dostęp do zmienny sesyjnych w danym pliku
if (!isset($_SESSION['loggedin']))
{
	header('Location: logowanie.php');
	exit();
}
//strona po zalogowaniu uzytkownika
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="fonts/fontawesome/fontawesome/css/all.css">
	<link rel="stylesheet" href="styles.css">
	<style>
	</style>
	<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4356854189231437"
     crossorigin="anonymous"></script>
</head>
<BODY>
	<script type = "text/javascript">
	window.onload = function() {
		if(!window.location.hash) {
			window.location = window.location + '#loaded';
			window.location.reload();
		}
	}
	</script>
	
	<div class="topbar">
		<p class='toptext'>PLATFORMA E-LEARNINGOWA</p>
	</div>
	
	<div class="content">
	
		<div class="sidecontent"> 
			<?php
				error_reporting(0);
				$link = mysqli_connect('', '', '', '');
				if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } //obsługa błędu połączenia z BD
				
				//informacja o tym kto jest zalogowany
				$username = $_SESSION['username'];
				echo "Zalogowano - " . $_SESSION['username'];
				
				echo "<br>----------------------------------------";
				date_default_timezone_set('Europe/Warsaw');
					
				//informacja o ostatniej probie wlamania sie na konto
				$breakins = mysqli_query($link, "SELECT * FROM break_ins WHERE username='$username' ORDER BY datetime DESC LIMIT 1"); // wiersze, w którym login=login z formularza
				foreach ($breakins as $row) {
					if($row['datetime'] != "") {
						echo "<br><p style='color: black';>Ostatnia próba włamania:<br>DATA: " . $row['datetime'] . "<br>IP: " . $row['ip'] . "</p>";			
					}
				}
				
				echo "<br><a href='logout.php'>Wyloguj się</a>";
				
				//pobieranie roli uzytkownika
				$roleQuery = mysqli_query($link, "SELECT * FROM user WHERE login='$username'");
				foreach ($roleQuery as $row) {
					$role = $row['role'];
				}
				
				//pobranie id zalogowanego uzytkownika
				$userquery = mysqli_query($link, "SELECT * FROM user WHERE login='$username'");
				foreach ($userquery as $row) {
					$currentidu= $row['idu'];
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
			if($role == 'admin'){
				//listy elementow, ktore admin moze usuwac
				echo "&ensp;Panel Administratora<br><br>";
				echo "<div class='lists'>";
					echo "<div class='list' style='background-color:#dffbf6;padding:5px;margin-right:5px;margin-left:5px'>";
						echo "Coach:<br>";
						$coachlist = mysqli_query($link, "SELECT * FROM user WHERE role = 'coach'");
						foreach ($coachlist as $row) {
							$login = $row['login'];
							echo "<br>• " . $login . " <a href='deleteuser.php?login=$login'>usuń</a>";
						}
					echo "</div>";
					echo "<div class='list' style='background-color:#dffbf6;padding:5px;margin-right:5px'>";
						echo "Pracownik:<br>";
						$pracowniklist = mysqli_query($link, "SELECT * FROM user WHERE role = 'pracownik'");
						foreach ($pracowniklist as $row) {
							$login = $row['login'];
							echo "<br>• " . $login . " <a href='deleteuser.php?login=$login'>usuń</a>";
						}
					echo "</div>";
					echo "<div class='list' style='background-color:#dffbf6;padding:5px;margin-right:5px'>";
						echo "Lekcja:<br>";
						$lessonlist = mysqli_query($link, "SELECT * FROM lesson");
						foreach ($lessonlist as $row) {
							$lessonname = $row['name'];
							echo "<br><a href='lessonpage.php?lessonname=$lessonname'>• " . $lessonname . "</a> <a href='deletelesson.php?lessonname=$lessonname'>usuń</a>";
						}
					echo "</div>";
					echo "<div class='list' style='background-color:#dffbf6;padding:5px;margin-right:5px'>";
						echo "Test:<br>";
						$testlist = mysqli_query($link, "SELECT * FROM test");
						foreach ($testlist as $row) {
							$testname = $row['name'];
							echo "<br>• <a href='testpage.php?testname=$testname'>" . $testname . "</a> <a href='deletetest.php?testname=$testname'>usuń</a>";
						}
					echo "</div>";
				echo "</div>";
			}
			
			if($role == 'coach'){
				//panel coacha - lekcje i testy
				echo "&ensp;Panel Coacha<br><br>";
				echo "<div class='columns' style='background-color:#dffbf6;padding:5px;margin-right:5px;margin-left:5px'>";
					echo "<div class='column'>";
						echo "<a href='newlesson.php'>Dodaj nową lekcję</a><br>";
						echo "Moje lekcje<br>";
						$lessonlist = mysqli_query($link, "SELECT * FROM lesson WHERE idu='$currentidu'");
						foreach ($lessonlist as $row) {
							$lessonname = $row['name'];
							echo "<br><a href='lessonpage.php?lessonname=$lessonname'>• " . $lessonname . "</a> <a href='editlesson.php?lessonname=$lessonname'>edytuj</a> <a href='deletelesson.php?lessonname=$lessonname'>usuń</a>";
						}
					echo "</div>";
					echo "<div class='column' style='background-color:#dffbf6;padding:5px;margin-right:5px;'>";
						echo "<a href='newtest.php'>Dodaj nowy test</a><br>";
						echo "Moje testy<br>";
						$testlist = mysqli_query($link, "SELECT * FROM test WHERE idu='$currentidu'");
						foreach ($testlist as $row) {
							$testname = $row['name'];
							echo "<br><a href='testpage.php?testname=$testname'>• " . $testname . "</a> <a href='edittest.php?testname=$testname'>edytuj</a> <a href='deletetest.php?testname=$testname'>usuń</a>";
						}
						
						echo "<br><br>Moje wyniki testów:<br>";
						$resultlist = mysqli_query($link, "SELECT * FROM result WHERE idu='$currentidu'");
						foreach ($resultlist as $row) {
							$idt = $row['idt'];
							//nazwa testu
							$testlist = mysqli_query($link, "SELECT * FROM test WHERE idt='$idt'");
							foreach ($testlist as $row2) {
								$testname = $row2['name'];
							}
							$filename = $row['pdf_file'];
							echo "<br>• " . $testname . " - " . $row['points'] . " - <a href='$filename' target='_blank'>" . $row['pdf_file'] . "</a>";
						}
					echo "</div>";
				echo "</div>";
			}
			
			if($role == 'pracownik'){
				//panel pracownika - wyniki testow
				echo "&ensp;Panel Pracownika<br><br>";
					echo "<div class='columns'>";
					echo "<div class='column' style='background-color:#dffbf6;padding:5px;margin-right:5px;margin-left:5px'>";
						echo "Moje wyniki testów:<br>";
						$resultlist = mysqli_query($link, "SELECT * FROM result WHERE idu='$currentidu'");
						foreach ($resultlist as $row) {
							$idt = $row['idt'];
							//nazwa testu
							$testlist = mysqli_query($link, "SELECT * FROM test WHERE idt='$idt'");
							foreach ($testlist as $row2) {
								$testname = $row2['name'];
							}
							$filename = $row['pdf_file'];
							echo "<br>• " . $testname . " - " . $row['points'] . " - <a href='$filename' target='_blank'>" . $row['pdf_file'] . "</a>";
						}
					echo "</div>";
				echo "</div>";
			}
			?>
		</div>
		
	</div>
</BODY>
</HTML>

<?php mysqli_close($link); ?>