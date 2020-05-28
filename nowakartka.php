<?php

	session_start();
	
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location:index.php');
		exit();
	}
	else
	{
		if(isset($_SESSION['login']))
		{
			$wszystko_dobrze=true;
			
			$title = $_POST['title'];
			if(!isset($_POST['title']))
			{
				$wszystko_dobrze=false;
				$_SESSION['e_title'] = "Notatka musi mieć tytuł!";
			}
			
			$text = $_POST['text'];
			if(!isset($_POST['text']))
			{
				$wszystko_dobrze=false;
				$_SESSION['e_text'] = "Notatka musi mieć tekst!";
			}	

			$wyslij = $_POST['wyslij'];
			if(!isset($_POST['wyslij']))
			{
				$wyslij = "nie";
				
			}
			else
			{
				$wyslij = "tak";
			}
	

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
						if($wszystko_dobrze==true)
						{	$autor = $_SESSION['autor'];
							if(!isset($autor))
							{
								$autor=$_SESSION['login'];
							}
							
							if($rezultat=@$polaczenie->query("INSERT INTO karteczka VALUES (NULL, '$autor', '$title', '$text',
							now() + INTERVAL 1 DAY, '$wyslij')"))
							{
								$_SESSION['udane']=true;
								if($wyslij = "tak")
								{
									$to = $_SESSION['email'];
									$subject = "Oto Twoja notatka z serwisu Notatki-online."."Tytuł: ".$title;
									$message = "Treść notatki: ".$title;
									$headers = array(
									'From' => 'srv32722@srv32722.microhost.com.pl',
									'Reply-To' => 'srv32722@srv32722.microhost.com.pl',
									'X-Mailer' => 'PHP/' . phpversion()
									);
									mail($to,$subject,$message,$headers);
									
								}
								header("Location: dodanienowej.php");
							}
							else
							{
								throw new Exception($polaczenie->error);
							}
						}
						$polaczenie->close();
					}
				}
				catch(Exception $e)
				{
					echo '<div class="error"> Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie </div>';
					echo '<br/> Informacja developerska: '.$e;
				}
		}
	}
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<title>Notatki - stwórz nową notatkę!</title>
	<link rel="stylesheet" href="main.css">
    <link href="https://fonts.googleapis.com/css?family=Lobster|Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
	<style>
		.error
		{
			color:red;
			margin-top: 10px;
			margin-bottom: 10px;
		}
	</style>
</head>
<body>
<div class="container">
<main>
	<form method="post">
	Tytuł Twojej notatki: <br/> <input type="text" name="title"/> <br/>
	
	<?php
		if(isset($_SESSION['e_title']))
		{
			echo '<div class="error">'.$_SESSION['e_title'].'</div>';
			unset($_SESSION['e_title']);
			
		}
	?>
	
	Tekst Twojej notatki: <br/> <input type="text" name="text"/> <br/>
	
	<?php
		if(isset($_SESSION['e_text']))
		{
			echo '<div class="error">'.$_SESSION['e_text'].'</div>';
			unset($_SESSION['e_text']);
			
		}
	?>
	
		<label>
		<input type="checkbox" name="wyslij"/> Zaznacz, jeśli chcesz wysłać notatkę na podanego maila
	</label>
	
	<?php
		if(isset($_SESSION['e_wyslij']))
		{
			echo '<div class="error">'.$_SESSION['e_wyslij'].'</div>';
			unset($_SESSION['e_wyslij']);
			
		}
	?>
	
	<br/>
	
	<input type="submit" value="Stwórz nową notatkę"/>
	</form>
</main>
</div>
</body>
</html>