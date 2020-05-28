<?php

	session_start();
	
	if((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))   
	{
		header('Location:mojekartki.php');
		exit();
	}
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<title>Notatki online - pamiętaj to!</title>
    <link rel="stylesheet" href="main.css">
    <link href="https://fonts.googleapis.com/css?family=Lobster|Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
</head>
<body>
<div class="container">
	<br/>
	<header>
	<h1>Strona do tworzenia i przechowywania popularnych StickyNotes.</h1>
	</header>
	<br/>
	<main>
	<a href="rejestracja.php">Rejestracja - załóż darmowe konto</a>
	<br/>
	<br/>
	<form action="zaloguj.php" method="post">
	
	Login: <br/> <input type="text" name="login"/> <br/>
	Hasło: <br/> <input type="password" name="haslo"/> <br/><br/>
	<input type="submit" value="zaloguj się"/>
	</form>
	
<?php
	if(isset($_SESSION['blad']))  
	echo $_SESSION['blad'];
?>
	</main>
</div>
</body>
</html>