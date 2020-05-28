<?php

	session_start();
	
	if((!isset($_SESSION['udane'])))
	{
		header('Location:index.php');
		exit();
	}
	else
	{
		unset($_SESSION['udane']);
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
	Dziękujemy za dodanie nowej notatki. Możesz ją zobaczyć na swojej stronie.
	<br/>
	<br/>
	
	<a href="mojekartki.php">Zobacz swoją nową notatkę!</a>
	<br/>
	<br/>
</main>	
</div>
</body>
</html>