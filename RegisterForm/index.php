<?php require("register.class.php") ?>
<?php
	// При наличии POST запроса вызываем конструктор класса Register.class
	if(isset($_POST['submit'])){
		$user = new RegisterUser($_POST['login'], $_POST['password'], $_POST['repass'], $_POST['email'],$_POST['username']);
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <link rel="stylesheet" href="styles.css">
	 <script src="jquery.min.js"></script>
	<title>Форма регистрации</title>
</head>
<body>

	<form id="reg_form" action="" method="post" enctype="multipart/form-data" autocomplete="off">
		<h2>Форма регистрации</h2>
		<h4>Все поля должны быть <span>заполнены</span></h4>
		<p class="error"><?php echo @$user->log_error ?></p>
		<label>Логин</label>
		<input type="text" name="login" id="i1" value="<?= $_POST['login'] ?? '' ?>">

		<p class="error"><?php echo @$user->pass_error ?></p>
		<label>Пароль</label> 
		<input type="password" name="password" id="2" value="<?= $_POST['password'] ?? '' ?>">
		
		<label>Потверждение пароля</label>
		<input type="password" name="repass" id="3" value="<?= $_POST['repass'] ?? '' ?>">

		<p class="error"><?php echo @$user->email_error ?></p>
		<label>Email</label> 
		<input type="text" name="email" id="4" value="<?= $_POST['email'] ?? '' ?>">

		<p class="error"><?php echo @$user->name_error ?></p>
		<label>Имя</label>
		<input type="text" name="username" id="5" value="<?= $_POST['username'] ?? '' ?>">


		<button type="submit" name="submit">Зарегистрироваться</button>

		<p class="error"><?php echo @$user->error ?></p>
		<p class="success"><?php echo @$user->success ?></p>

	</form>
	<script>
		$.ajax({
			method: "POST",
			url: "register.class.php",
			data: {  }
		})
			.done(function(  msg ) {
				//alert( "Ajax запрос выполнен " + msg );
			});
	</script>

</body>
</html>