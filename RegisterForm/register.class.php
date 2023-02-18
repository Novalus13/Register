<?php 
class RegisterUser{
	// Поля класса
	private $login;
	private $username;
	private $raw_password;
	private $repass;
	private $encrypted_password;
	private $email;
	public $error;
	public $log_error;
	public $pass_error;
	public $email_error;
	public $name_error;
	public $success;
	private $storage = "data.json";
	private $stored_users;
	private $new_user; // массив пользователей


	public function __construct($login, $password,$repass ,$email,$username){
		$this->username = trim($this->username);
		$this->login = trim($this->login);
		$this->login = filter_var($login, FILTER_SANITIZE_STRING);
		$this->repass= $repass;

		// шифруем пароль
		$this->raw_password = filter_var(trim($password), FILTER_SANITIZE_STRING);
		$this->encrypted_password = password_hash($this->raw_password, PASSWORD_DEFAULT);
		$this->email = $email;
		$this->username = $username;


		$this->stored_users = json_decode(file_get_contents($this->storage), true);

		$this->new_user = [
			"login" => $this->login,
			"password" => $this->encrypted_password,
			"email" => $this->email,
			"username" => $this->username
		];
		// вызываем метод проверки полей, если ошибок в полях регистрации нет - добавляем пользователя в БД
		if($this->checkFieldValues()){
			$this->insertUser();
		}
	}

// Проверяем правильность заполнения полей регистрации
	private function checkFieldValues(){
        // Проверка является ли запрос формата Ajax
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

            die('Только Ajax запросы');
        }



        if(empty($this->login) && empty($this->raw_password) && empty($this->email) && empty($this->username)){
			$this->error = "Необходимо заполнить все поля";
			return false;
		}
		

		if(empty($this->login)){
			$this->log_error = 'Поле: "Логин" не заполнено';
			return false;
		}
		
		if(!preg_match('/^[a-zA-Z0-9]+$/',$this->login)){
			$this->error = "Логин может состоять только из цифр и букв";
			return false;
		}
		
		if(mb_strlen($this->login)<6){
			$this->error = "Логин должен состоять минимум из 6 символов";
			return false;
		} 


		if(empty($this->raw_password)){
			$this->pass_error = 'Поле: "Пароль" не заполнено';
			return false;
		}

		
		if(empty($this->repass)){
			$this->pass_error = 'Поле: "Потверждение пароля" не заполнено';
			return false;
		}
		
		if($this->raw_password != $this->repass){
			$this->pass_error = 'Поля: "Пароль" и "Потверждения пароля" не совпадают';
			return false;
		}
						
		
		if(mb_strlen($this->raw_password)<6){
			$this->error = "Пароль должен состоять минимум из 6 символов";
			return false;
		}

        if(!preg_match('/[A-zА-я]+/',$this->raw_password)){
            $this->pass_error = "Пароль должен содержать минимум одну букву";
            return false;

        }

		if(!preg_match('/[0-9]+/',$this->raw_password)){
            $this->pass_error = "Пароль должен содержать минимум одну цифру";
            return false;

		}

		if(empty($this->email)){
			$this->email_error = "Поле: Email не заполнено";
			return false; 
		} 

		if(!filter_var($this->email,FILTER_VALIDATE_EMAIL)){
			$this->email_error = "Некорректно указан Email";
			return false;
		} 
		if(empty($this->username)){
			$this->name_error = "Поле: Имя не заполнено";
			return false;
		}


		if(!preg_match('/[A-zА-я]+/',$this->username)){
			$this->name_error = "Имя должно состоять только из букв";
			return false;
		} 


		if(mb_strlen($this->username)<2){
			$this->name_error = "Имя должно содержать минимум 2 буквы";
			return false;
		} 

		return true;
	
	
	
	}




		
	
// Методы проверяющий БД на отсуствие уже зарегестрированных пользователей с текущим Login\Email

	private function loginExists(){
		foreach($this->stored_users as $login){
			if($this->login == $login['login']){
				$this->error = "Текущий логин занят, выберете другой.";
				return true;
			}
		}
		return false;
	}

	private function emailExists(){
		foreach($this->stored_users as $email){
			if($this->email == $email['email']){
				$this->email_error = "Текущий Email уже зарегистрирован, укажите другой";
				return true;
			}
		}
		return false;
	}

    // Метод добавляющий данные в БД
	private function insertUser(){
		if($this->loginExists() == FALSE) {
            if ($this->emailExists() == FALSE) {
                array_push($this->stored_users, $this->new_user);
                if (file_put_contents($this->storage, json_encode($this->stored_users, JSON_PRETTY_PRINT))) {
                    return $this->success = "Регистрация успешно завершена";
                } else {
                    return $this->error = "Что-то пошло не так, попробуйте еще раз";
                }
            }
        }
	}


}