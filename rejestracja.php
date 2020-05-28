<?php

	session_start(); 
	
	if(isset($_POST['email']))  
	{

		$wszystko_ok=true;
		

		$nick=$_POST['nick'];

		if((strlen($nick)<3) || (strlen($nick)>20))  
		{
			$wszystko_ok=false;   
			$_SESSION['e_nick']="Nick musi posiadać od 3 do 20 znaków!"; 
		}

		if(ctype_alnum($nick)==false)
		{
			$wszystko_ok=false;  
			$_SESSION['e_nick']="Nick może zawierać wyłącznie litery i cyfry, bez polskich znaków"; 
		}
		
		$email=$_POST['email'];
		$emailB=filter_var($email,FILTER_SANITIZE_EMAIL); 
		
		if((filter_var($emailB,FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email)) 
		{
			$wszystko_ok=false;
			$_SESSION['e_email']="Podaj poprawny adres email!";
		}
		
		$haslo1=$_POST['haslo1'];
		$haslo2=$_POST['haslo2'];
		
		if((strlen($haslo1)<8) || (strlen($haslo1)>20)) 
		{
			$wszystko_ok=false;
			$_SESSION['e_haslo']="Hasło musi posiadać od 8 do 20 znaków!";
		}
		
		if($haslo1!=$haslo2)  
		{
			$wszystko_ok=false;
			$_SESSION['e_haslo']="Hasła nie są identyczne!";
		}		
		
		$haslo_hash=password_hash($haslo1,PASSWORD_DEFAULT); 
		
		if(!isset($_POST['regulamin']))  
		{
			$wszystko_ok=false;
			$_SESSION['e_regulamin']="Potwierdź akceptację regulaminu!";
		}			
		
		$secret = '........................';
		
		$sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
		
		$odpowiedz=json_decode($sprawdz);  
		
		if($odpowiedz->success==false)  
		{
			$wszystko_ok=false;
			$_SESSION['e_bot']="Potwierdź, że nie jesteś botem!";
		}
		
		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT); 
		
		try
		{
			$polaczenie = new mysqli($host,$db_user,$db_password,$db_name); 
			if($polaczenie->connect_errno!=0) 
			{
				throw new Exception(mysqli_connect_errno()); 
			}
			else
			{
				$rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE email='$email'"); 
				
				if(!$rezultat)throw new Exception($polaczenie->error); 
				
				$ile_takich_maili = $rezultat->num_rows; 
				if ($ile_takich_maili>0)
				{
					$wszystko_ok=false;  
					$_SESSION['e_email']="Istnieje już konto przypisane do tego adresu email!";
				}
				
				$rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE login='$nick'");
				
				if(!$rezultat)throw new Exception($polaczenie->error);
				
				$ile_takich_nickow = $rezultat->num_rows;
				if ($ile_takich_nickow>0)
				{
					$wszystko_ok=false;
					$_SESSION['e_nick']="Wybrany nick jest już zajęty!";
				}
				if($wszystko_ok==true)
				{
					if($polaczenie->query("INSERT INTO uzytkownicy VALUES (NULL,'$nick','$haslo_hash','$email')"))
					{
						$_SESSION['udanarejestracja']=true;
						header("Location: witamy.php");
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
		}
	}
	
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<title>Notatki - załóż darmowe konto</title>
	<link rel="stylesheet" href="main.css">
    <link href="https://fonts.googleapis.com/css?family=Lobster|Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
	Autor notatki: <br/> <input type="text" name="nick"/> <br/>
	
	<?php
		if(isset($_SESSION['e_nick']))
		{
			echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
			unset($_SESSION['e_nick']);
			
		}
	?>
	
	Twój e-mail: <br/> <input type="text" name="email"/> <br/>
	
	<?php
		if(isset($_SESSION['e_email']))
		{
			echo '<div class="error">'.$_SESSION['e_email'].'</div>';
			unset($_SESSION['e_email']);
			
		}
	?>
	
	Twoje hasło: <br/> <input type="password" name="haslo1"/> <br/>
	
	<?php
		if(isset($_SESSION['e_haslo']))
		{
			echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
			unset($_SESSION['e_haslo']);
		}
	?>
	
	Powtórz hasło: <br/> <input type="password" name="haslo2"/> <br/>
	
	<label>
		<input type="checkbox" name="regulamin"/> Akceptuję regulamin
	</label>
	<?php
		if(isset($_SESSION['e_regulamin']))
		{
			echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
			unset($_SESSION['e_regulamin']);
			
		}
	?>

	<div class="g-recaptcha" data-sitekey="....................................."></div>
	<?php
		if(isset($_SESSION['e_bot']))
		{
			echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
			unset($_SESSION['e_bot']);
			
		}
	?>
	<br/>
	
	<input type="submit" value="Zarejestruj się"/>
	</form>
</main>
</div>
</body>
</html>