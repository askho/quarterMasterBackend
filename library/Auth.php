<?php
class Auth {
	private $conn;
	function __construct() {
		require_once "DBInfo.php";
		$this->conn = $DBConn;
		session_start();
	}
	public function isAuthenticated() {
		if(session_status() == PHP_SESSION_NONE || !isset($_SESSION["authToken"])) {
			return false;
		}
		if(!$sql = $this->conn->prepare("SELECT authToken FROM user WHERE userName=?"))
			return false;

		if(!$sql->bind_param('s', $_SESSION["user"]))
			return false;
		if(!$sql->execute())
			return false;
		if(!$result = $sql->get_result())
			return false;
		if ($result->num_rows == 1) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				if($row["authToken"] == $_SESSION["authToken"]) {
					return true;
				}
			}
		}
		return false;
	}

	public function login($user, $pass) {
		$user = mysqli_real_escape_string($this->conn, $user);
		$pass = mysqli_real_escape_string($this->conn, $pass);
		if(session_status() != PHP_SESSION_NONE) {
			$this->logout();
		}

		if(!$sql = $this->conn->prepare("SELECT password FROM user WHERE userName=?"))
			return false;

		if(!$sql->bind_param('s', $user))
			return false;

		if(!$sql->execute())
			return false;

		if(!$result = $sql->get_result()) {
			return false;
		}
		if ($result->num_rows == 1) {
			while($row = $result->fetch_assoc()) {
				if(!password_verify($pass, $row["password"])){
					return false;
				}
			}
		}
		session_start();
		$_SESSION["authToken"] = md5(uniqid(rand(), true)); //Generate random token for authID
		$_SESSION["user"] = $user;
		if(!$sql = $this->conn->prepare("UPDATE user set authToken=? WHERE userName=?"))
			return false;
		if(!$sql->bind_param('ss', $_SESSION["authToken"], $user))
			return false;
		if(!$sql->execute()){
		    return false;
		}
		return true;

	}
	/**
	 * @param  string $user [The username of the password that we want to change]
	 * @param  string $oldPass [The older password of the user]
	 * @param  string $newPass [The new password of the user]
	 * @return bool returns true if succesfully changed password
	 */
	public function changePassword($user, $oldPass, $newPass) {
		$user = mysqli_real_escape_string($this->conn, $user);
		$oldPass = mysqli_real_escape_string($this->conn, $oldPass);
		$newPass = mysqli_real_escape_string($this->conn, $newPass);
		if(!$sql = $this->conn->prepare("SELECT password FROM user WHERE userName=?"))
			return false;
		if(!$sql->bind_param('s', $user))
			return false;
		if(!$sql->execute())
			return false;
		if(!$result = $sql->get_result())
			return false;
		if ($result->num_rows == 1) {
			while($row = $result->fetch_assoc()) {
				if(!password_verify($oldPass, $row["password"])){
					return false;
				}
			}
		} else {
			return false;
		}
		$hashAndSalt = password_hash($newPass, PASSWORD_BCRYPT);
		if(!$sql = $this->conn->prepare("UPDATE user set password=? WHERE userName=?"))
			return false;
		if(!$sql->bind_param("ss", $hashAndSalt, $user))
			return false;
		if(!$sql->execute())
			return false;
		$this->logout();
		$this->login($user, $newPass);
	    return true;
	}

	public function logout() {
		if(session_status() == PHP_SESSION_NONE)
			return false;
		session_unset();
		session_destroy();
		return true;
	}
	/**
	 * Registers a user
	 * @param  [string] $user    [The username]
	 * @param  [string] $pass    [The password]
	 * @param  [string] $name    [The name]
	 * @param  [string] $address [The address]
	 * @param  [string] $phone   [The phone]
	 * @param  [string] $email   [The email]
	 * @return [int]             [The return code. 1 = Okay, 1062 = duplicate key]
	 */
	public function register($user, $pass, $name, $address, $phone, $email) {
		$user = mysqli_real_escape_string($this->conn, $user);
		$pass = mysqli_real_escape_string($this->conn, $pass);
		$pass = password_hash($pass, PASSWORD_BCRYPT);
		$name = mysqli_real_escape_string($this->conn, $name);
		$address = mysqli_real_escape_string($this->conn, $address);
		$phone = mysqli_real_escape_string($this->conn, $phone);
		$email = mysqli_real_escape_string($this->conn, $email);

		if(!$sql = $this->conn->prepare("INSERT INTO user (userName, password, name, address, phone, email)"
			. "VALUES (?, ?, ?, ?, ?, ?)"))
			return false;
		if(!$sql->bind_param('ssssss', $user, $pass, $name, $address, $phone, $email))
			return false;
		$sql->execute();
		return $sql->errno;
	}
}
?>