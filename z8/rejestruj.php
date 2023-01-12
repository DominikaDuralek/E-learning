<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<BODY>
	Rejestracja
	<form method="post" action="addnewpracownik.php">
		Login:<input type="text" name="user" maxlength="20" size="20"><br>
		Hasło:<input type="password" name="pass" maxlength="20" size="20"><br>
		Powtórz:<input type="password" name="confirm_pass" maxlength="20" size="20"><br>
		<input type="hidden" id="role" name="role" value="pracownik">
		<input type="submit" value="Send"/>
		<br><a href="index.php">Strona główna zadania</a>
	</form>
</BODY>
</HTML>
