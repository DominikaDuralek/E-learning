<?php
session_start();

if(!isset($_SESSION['loginAttempts'])){
	$_SESSION['loginAttempts'] = 0;
}


if(isset($_SESSION['loginDisabled'])) //jesli login aktualnie zablokowany
{
	if(time() - $_SESSION['loginDisabled'] > 60) //jesli czas juz minal
	{
		$_SESSION['loginAttempts'] = 0;
		unset($_SESSION['loginDisabled']);
	}
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<BODY>

	Formularz logowania
	<form method="post" action="weryfikuj.php">
		Login:<input type="text" name="user" maxlength="20" size="20"><br>
		Hasło:<input type="password" name="pass" maxlength="20" size="20"><br>
		<?php
		if(isset($_SESSION['loginDisabled']))
		{
			?>
			<input type="submit" value="Send" disabled />
			<?php
			echo "Logowanie zablokowane na minutę!";
		}
		else
		{
			?>
			<input type="submit" value="Send" />
			<?php
			echo "Nieudane próby zalogowania: " . $_SESSION['loginAttempts'];
		}
		?>
		<br>
		<a href ="rejestruj.php">Rejestracja</a><br />
		<a href ='index.php'>Strona główna zadania</a><br />
	</form>
</BODY>
</HTML>

