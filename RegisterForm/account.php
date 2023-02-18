<?php 
	session_start();
	if(!isset($_SESSION['login'])){
		header("location: login.php");	exit();
	}

	if(isset($_GET['logout'])){
		unset($_SESSION['login']);
		header("location: login.php");	exit();
	}

	
	if(!empty($_COOKIE)){
		header("location:login.php");
	}

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <link rel="stylesheet" href="styles.css">
	<title>Аккаунт пользователя</title>
</head>
<body>

	<div class="content">
		<header>
			<h2>Hello <?php echo $_SESSION['username']; ?><h2>
			<a href="?logout">Выйти</a>	
		</header>

		<main>
			<h3></h3>
		</main>
	</div>

</body>
</html>