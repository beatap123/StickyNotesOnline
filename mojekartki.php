<?php

	session_start();
	
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location:index.php');
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
<main>
<?php

	require_once "connect.php";
	try
		{
			$polaczenie = new mysqli($host,$db_user,$db_password,$db_name);
			if($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				$autor = $_SESSION['autor'];
				if(!isset($autor))
				{
					$autor=$_SESSION['login'];
				}
				$rezultat = $polaczenie->query("SELECT DISTINCT * FROM uzytkownicy, karteczka WHERE autor='$autor' GROUP BY date");

				if(!$rezultat)throw new Exception($polaczenie->error);
				
				$ile_takich_kartek = $rezultat->num_rows;

				if ($ile_takich_kartek>0)
				{
					echo "<p>Witaj, ".$_SESSION['autor'].'! [<a href="logout.php">Wyloguj 
						się</a>]</p>';
					echo "<br/>Znajdziesz tu wszystkie Twoje notatki<br/>";
				for ($number=1;$number<=$ile_takich_kartek;$number++)
					{
					$wiersz=$rezultat->fetch_assoc(); 
					$_SESSION['autor']=$wiersz['autor']; 
					$_SESSION['title']=$wiersz['title']; 
					$_SESSION['text']=$wiersz['text']; 
					$_SESSION['date']=$wiersz['date']; 
					$_SESSION['login']=$wiersz['login'];
					$_SESSION['email']=$wiersz['email'];
					
					$dataczas = new DateTime(); 
						
					$koniec = DateTime::createFromFormat('Y-m-d H:i:s',$_SESSION['date']);
	
					$roznica = $dataczas->diff($koniec);
						
						if($dataczas<$koniec)
						{
							echo "<br/><b>Notatka ważna jeszcze: </b><br/>".$roznica->format('%h godzin %i minut %s sekund');
						
?>
					<br/>
					<table>		
					<br/>					
					<thead><tr><th colspan="3">Notatka numer <?php echo "{$number}";  ?></th></tr>
						<tr><th>Tytuł</th><th>Tekst</th><th>Data wygaśnięcia</th></tr></thead>
					<tbody>
					<?php
					 echo "<tr><td>{$_SESSION['title']}</td><td>{$_SESSION['text']}</td><td>{$_SESSION['date']}</td></tr>"; 
					?>
					<tbody>
					</table>
<?php					
						}	
						else
						{
							$usun = $polaczenie->query("DELETE FROM karteczka WHERE autor='$autor' AND '$Sdataczas' > date");
							echo "<br/>Notatka <b>{$_SESSION['title']}</b> już wygasła<br/>";
						}
					}
					echo "<p>Nowy pomysł? ".'[<a href="nowakartka.php">Stwórz nową notatkę
					</a>]</p>'; 
				}
				elseif ($ile_takich_kartek==0)
				{	
					$login = $_SESSION['login'];
					$wynik=@$polaczenie->query(  
					sprintf("SELECT * FROM uzytkownicy WHERE login='%s'",  
					mysqli_real_escape_string($polaczenie,$login)
					));
					$wiersz=$wynik->fetch_assoc();  
					$_SESSION['login']=$wiersz['login'];
					$_SESSION['email']=$wiersz['email'];
					echo "<p>Witaj, ".$_SESSION['login'].'![<a href="logout.php">Wyloguj się</a>]</p>'; 
					echo "<p>Nowy pomysł? ".'[<a href="nowakartka.php">Stwórz nową notatkę
					</a>]</p>'; 					
				}
				$polaczenie->close();
			}
		}
		catch(Exception $e)
		{
			echo '<div class="error"> Błąd serwera! Przepraszamy za niedogodności </div>';
			echo '<br/> Informacja developerska: '.$e;
		}	
?>
</main>
</div>	
</body>
</html>