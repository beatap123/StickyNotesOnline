<?php

	session_start();
	
	if((!isset($_SESSION['udanarejestracja'])))
	{
		header('Location:index.php');
		exit();
	}
	else
	{
		unset($_SESSION['udanarejestracja']);
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
<main>
	Dziękujemy za rejestrację. <br/> Możesz teraz zalogować się na swoje konto.
	<br/>
	<br/>
	
	<a href="index.php">Zaloguj się na swoje konto!</a>
	<br/>
	<br/>
</main>
</div>
</body>
</html>