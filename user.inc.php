<?php

	//require_once 'connectdb.inc.php';
	
	class User{

		private $uid;		//user id
		private $fields;	//other record fields

		//initialize a User object

		public function __construct(){
			$this->uid = null;
			$this->fields = array('first_name' => '',
								  'last_name' => '',
								  'emailAddr' => '',
								  'genre' => '',
								  'matricule'=>'',
								  'employment_type'=>'',
								  'nationality'=>'',
								  'function'=>'',
								  'process'=>'',
								  'manager'=>'',
								  'social_security'=>'',
								  'date'=>'',
								  'password'=>'',
								  'inVacation'=>false
								);
		}

		//override magic method to retrieve properties

		public function __get($field){
			if ($field == 'userId') {
				return $this->uid;
			}else{
				return $this->fields[$field];
			}
		}

		//override magic method to set properties
 
		public function __set($field, $value){

			if (array_key_exists($field, $this->fields)) {
				
				$this->fields[$field] = $value;
			}
		}

		//return if user1stName is valid format

		public static function validateString($string){

			return preg_match('/^[A-Z0-9]{2,20}$/i', $string);
		}

		//return if email is valid format

		public static function validateEmail($email){

			return filter_var($email, FILTER_VALIDATE_EMAIL);
		}

		//return an object populated based on the record's user id

		public static function getByUserM($userMatricule){

			$user = new User();
			$query = sprintf('SELECT * FROM user_rms WHERE userMatricule = "%s"', mysqli_real_escape_string($GLOBALS['DB'], $userMatricule));
			$result = mysqli_query($GLOBALS['DB'],$query);
			if (mysqli_num_rows($result)) {
				$row = mysqli_fetch_assoc($result);
				$user->first_name = $row['user1stName'];
				$user->last_name = $row['userLastName'];
				$user->emailAddr = $row['userEmail'];
				$user->inVacation = $row['inVacation'];
				$user->function = $row['userFunction'];
				$user->uid = $row['userId'];
				$user->matricule = $userMatricule;
				
			}

			mysqli_free_result($result);
			return $user;
		}

		// public static function getByuser1stName($user1stName){
		
		// $user = new User();
  //       $query = sprintf('SELECT userId, userLastName, userEmail, inVacation FROM user_rms WHERE user1stName = "%s"',mysqli_real_escape_string($GLOBALS['DB'], $user1stName));
  //       $result = mysqli_query($GLOBALS['DB'],$query);

  //       if (mysqli_num_rows($result))
  //       {
  //           $row = mysqli_fetch_assoc($result);
  //           $user->user1stName = $user1stName;
  //           $user->userLastName = $row['userLastName'];
  //           $user->emailAddr = $row['userEmail'];
  //           $user->inVacation = $row['inVacation'];
  //           $user->uid = $row['userId'];
  //       }

  //       mysqli_free_result($result);
  //       return $user;
		// 	}

		public static function getByEmail($userEmail){

			$user = new User();
			$query = sprintf('SELECT * FROM user_rms WHERE userEmail = "%s"', mysqli_real_escape_string($GLOBALS['DB'], $userEmail));
			$result = mysqli_query($GLOBALS['DB'],$query);
			if (mysqli_num_rows($result)) {
				
				$row = mysqli_fetch_assoc($result);
				$user->uid = $row['userId'];
				$user->first_name = $row['user1stName'];
				$user->last_name = $row['userLastName'];
				$user->inVacation = $row['inVacation'];
				$user->password = $row['password'];
				$user->emailAddr = $userEmail;
			}
				
			mysqli_free_result($result);
			return $user;
		}

		//save the record to the database

		public function save(){
			
			if ($this->uid) {

				$query = sprintf('UPDATE user_rms SET user1stName = "%s", userLastName = "%s", userEmail = "%s", inVacation = %d WHERE userId = %s', mysqli_real_escape_string($GLOBALS['DB'],$this->uid), mysqli_real_escape_string($GLOBALS['DB'],$this->user1stName), mysqli_real_escape_string($GLOBALS['DB'],$this->userLastName), mysqli_real_escape_string($GLOBALS['DB'],$this->emailAddr), $this->inVacation, $this->userId);
				$result = mysqli_query($GLOBALS['DB'],$query);
				}else{

				$query = sprintf('INSERT INTO user_rms (user1stName, userLastName, userEmail, inVacation) VALUES ("%s", "%s", "%s", %d)', mysqli_real_escape_string($GLOBALS['DB'],$this->user1stName), mysqli_real_escape_string($GLOBALS['DB'],$this->userLastName), mysqli_real_escape_string($GLOBALS['DB'],$this->emailAddr), $this->inVacation);
				if (mysqli_query($GLOBALS['DB'],$query)) {
					$this->uid = mysqli_insert_id($GLOBALS['DB']);
					return true;
				}else{
					return false;
				}
			}
		}

		//set the record as inative and return an activation token

		// public function setInWork(){

		// 	$this->inVacation = false;
		// 	$this->save();

		// 	$token = random_text(7);
		// 	$query = sprintf('INSERT INTO pending (userId, token) VALUES (%d, "%s")', $this->uid, mysqli_real_escape_string($GLOBALS['DB'],$token));
		// 	return (mysqli_query($GLOBALS['DB'],$query)) ? $token:false;

		// }

		public function VRequest($duration,$type,$user){

					
			$query = sprintf('INSERT INTO pendingVRequest(vacationDuration, typeId, userId) VALUES (%d, %d, %d)', $duration, $type, $user);
			if (!mysqli_query($GLOBALS['DB'], $query)) {
					return false;
				}
				else{
					mysqli_insert_id($GLOBALS['DB']);
					return true;
				}
		}

		public function setInVacation($token){

			$query = sprintf('SELECT requestId FROM pendingVRrquest WHERE userId = %d', $this->uid);
			$result = mysqli_query($GLOBALS['DB'],$query);

			if (!mysqli_num_rows($result)) {
				
				mysqli_free_result($result);
				return false;
			}else{

				mysqli_free_result($result);
				$query = sprintf('DELETE FROM pendingVRrquest WHERE userId = %d', $this->uids);
				if (!mysqli_query($GLOBALS['DB'],$query)) {
					
					return false;
				}else{

					$this->inVacation = true;
					return $this->save();
				}

			}
		}
	}
	printf("Ok");
?>