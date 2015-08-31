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
		$sql = "SELECT authToken FROM user WHERE userName='{$_SESSION["user"]}'";
		$result = $this->conn->query($sql);
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
		if(session_status() != PHP_SESSION_NONE) {
			$this->logout();
		}
		$sql = "SELECT password FROM user WHERE userName='{$user}'";
		$result =  $this->conn->query($sql);
		if ($result->num_rows == 1) {
			while($row = $result->fetch_assoc()) {
				if(!password_verify($pass, $row["password"])){
					return false;
				}
			}
		} else {
			return false;
		}
		session_start();
		$_SESSION["authToken"] = md5(uniqid(rand(), true)); //Generate random token for authID
		$_SESSION["user"] = $user;
		$sql = "UPDATE user set authToken='{$_SESSION["authToken"]}' WHERE userName='{$user}'";
		if ($this->conn->query($sql) === FALSE) {
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
		$sql = "SELECT password FROM user WHERE userName='{$user}'";
		$result =  $this->conn->query($sql);
		if ($result->num_rows == 1) {
			while($row = $result->fetch_assoc()) {
				if(!password_verify($oldPass, $row["password"])){
					return false;
				}
			}
		} else {
			return false;
		}
		$result =  $this->conn->query($sql);
		$hashAndSalt = password_hash($newPass, PASSWORD_BCRYPT);
		$sql = "UPDATE user set password='{$hashAndSalt}' WHERE userName='{$user}'";
		if ($this->conn->query($sql) === TRUE) {
		    return true;
		}
		return false;
	}

	public function logout() {
		if(session_status() == PHP_SESSION_NONE)
			return false;
		session_unset();
		session_destroy();
		return true;
	}
}
?>