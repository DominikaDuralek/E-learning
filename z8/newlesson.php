<?php declare(strict_types=1); //włączenie typowania zmiennych w PHP >=7
session_start(); //zapewnia dostęp do zmienny sesyjnych w danym pliku
if (!isset($_SESSION['loggedin']))
{
	header('Location: logowanie.php');
	exit();
}
//dodawanie lekcji przez coacha
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="fonts/fontawesome/fontawesome/css/all.css">
	<link rel="stylesheet" href="styles.css">
	<style>
	</style>
	<script src="https://cdn.ckeditor.com/ckeditor5/35.4.0/classic/ckeditor.js"></script>
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
				echo "Dodawanie nowej lekcji:<br><br>
				<form method='post' action='addnewlesson.php' enctype='multipart/form-data'>
					Nazwa:&nbsp;<input type='text' name='name' maxlength='50' size='100'><br>
					
					<script type='text/javascript' src='/ckeditor/ckeditor.js'></script>
					<textarea name='text' id='editor'></textarea>
					
					Plik: <input type='file' name='uploaded_file' id='uploaded_file'><br><br>
					<input type='submit' value='Send' />
				</form>
				
				<script>
					ClassicEditor
						.create( document.querySelector( '#editor' ) )
						.catch( error => {
							console.error( error );
							} );
					</script
					";
				
			?>
		</div>
		
	</div>
</BODY>
</HTML>

<?php mysqli_close($link); ?>