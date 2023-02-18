<?php 
class LoginUser{
	

	private $login;
	private $username;
	private $password;
	public $error;
	public $success;
	private $storage = "data.json";
	private $stored_users;

	
	public function __construct($login, $password){
		$this->login = $login;
		$this->password = $password;
		$this->stored_users = json_decode(file_get_contents($this->storage), true);
		$this->login();
	}


	private function login(){
		foreach ($this->stored_users as $login) {
			if($login['login'] == $this->login){
				if(password_verify($this->password, $login['password'])){
					session_start();
					$_SESSION['login'] = $this->login;
                    $_SESSION['username'] = $this->username;
                    setcookie('login',$this->login,0,'/');
                    setcookie('password',$this->password,0,'/');


                    header("location: account.php"); exit();

				}
			}
		}
		return $this->error = "Логин или пароль указаны неверно";
	}

}