<?php
	
	session_start(); 
	
	if ((!isset($_POST['login'])) || (!isset($_POST['haslo'])))   
	{
		header('Location:index.php');  
		exit(); 
	}
	
	require_once "connect.php";   

	$polaczenie = @new mysqli($host,$db_user,$db_password,$db_name);  
	
	if($polaczenie->connect_errno!=0)  
	{
		echo "Error:".$polaczenie->connect_errno;  
	}
	else   
	{
		$login = $_POST['login'];  
		$haslo = $_POST['haslo'];  
		
		$login = htmlentities($login, ENT_QUOTES, "UTF-8");  
		
		if($rezultat=@$polaczenie->query(  
		sprintf("SELECT * FROM uzytkownicy WHERE login='%s'",  
		mysqli_real_escape_string($polaczenie,$login) 
		)))  
		{
			$ilu_userow=$rezultat->num_rows; 
			if($ilu_userow>0) 
			{
				$_SESSION['zalogowany']=true;  
				$wynik2=@$polaczenie->query(   
				sprintf("SELECT * FROM karteczka, uzytkownicy WHERE login='%s' AND login=autor",  
				mysqli_real_escape_string($polaczenie,$login)
				));
				$ile_takich_osob = $wynik2->num_rows;
				if ($ile_takich_osob>0)
				{
					$wiersz=$wynik2->fetch_assoc();  
					$_SESSION['id']=$wiersz['id']; 
					$_SESSION['autor']=$wiersz['autor']; 
					$_SESSION['title']=$wiersz['title']; 
					$_SESSION['text']=$wiersz['text']; 
					$_SESSION['date']=$wiersz['date'];  
					$_SESSION['email']=$wiersz['email']; 
					$_SESSION['login']=$wiersz['login'];
				}
				elseif ($ile_takich_osob==0)
				{
					$wynik3=@$polaczenie->query(   
					sprintf("SELECT * FROM uzytkownicy WHERE login='%s'",  
					mysqli_real_escape_string($polaczenie,$login)
					));
					$wiersz2=$wynik3->fetch_assoc();  
					$_SESSION['login']=$wiersz2['login'];
					$_SESSION['email']=$wiersz2['email'];
				}
				
				unset($_SESSION['blad']); 
				
				$rezultat->free_result();
				$wynik2->free_result();  
				
									
				header('Location:mojekartki.php');          

			} else {  
				
				$_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub 
				hasło!</span>';  
				header('Location:index.php'); 
				
			}
		}
		
		$polaczenie->close(); 
	}

?>