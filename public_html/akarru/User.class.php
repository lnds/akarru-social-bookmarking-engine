<?php
/**
 * @package AkarruCPE
 * @subpackage User
 * @version 0.6
 * @copyright (c) 2006 Eduardo Diaz Cortes
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License 
 * @author Eduardo Diaz <ediaz@lnds.net>
 */

/**
 * User maps all the details of a given user.
 */
class User {

	var $uid;
	var $data;
	var $key = AES_KEY;

	/**
	 * Create a User object.
	 * If user_id == 0 then the user is the current logged in user, else
	 * the user is loaded from the database
	 * @param $user_id 0 if you want to load the logged user 
	 */
	public function __construct($user_id=0, $user_name='', $data='')
	{
		$db = Database::singleton();
		$this->uid = $user_id;
		if (!empty($user_name)) 
		{
			$user_name = $db->sanitize($user_name);
			$this->data = $db->fetch_object("select * from users where username = '$user_name'");
		}
		else if ($this->uid == 0) 
		{
			$this->uid = $_SESSION['user_id'];
			if ($this->uid > 0) 
				$this->data = $db->fetch_object('select * from users where ID = '.$this->uid);
			else
			{
				$psession = $_COOKIE["bm_login_cookie"];
				if (isset($psession))
				$this->data = $db->fetch_object("select * from users where persistent_session = '$psession'");
				$_SESSION['user_id'] = $this->data->ID;
			}
		}
		else
		{
			if (empty($data)) 
				$this->data = $db->fetch_object('select * from users where ID = '.$this->uid);
			else
				$this->data = $data;
		}
	}

	/**
	 * @return a user if is logged, else null
	 */
	public static function login($username, $pass, $remember)
	{

		$key = AES_KEY;
		$domain = DOMAIN;
	   $db = Database::singleton();
	   $username = $db->sanitize(strtolower($username));
	   $pass = $db->sanitize(strtolower($pass));
	   $data = $db->fetch_object("select * from users where lower(username)='$username' and aes_decrypt(strong_pass, md5(join_date || '$key')) = md5('$pass')"); 
	   if (!isset($data) || empty($data->ID))
	   {
		   return false;
	   }
	   $_SESSION['user_id'] = $data->ID;
	   if (!empty($remember))
	   {
			$psession = 'ses:'.md5($data->username . '-' . time());
			$uid = $data->ID;
			$db->execute("update users set persistent_session = '$psession' where ID = $uid");
			setcookie('bm_login_cookie', $psession, time()+24*60*60*30, '/', $domain);
	   }

	   $user = new User($data->ID, $data->username, $data);
	   return $user;
	}


	public static function gen_password($email, $subject, $body, $login_url)
	{
		$db = Database::singleton();
		$email = $db->sanitize($email);
		$user = $db->fetch_object("select * from users where email = '$email' limit 1");
		if ($user) 
		{
			$pass = mb_substr(base64_encode(md5($time().$user->password.$user->email.$user->id.time())), 0, 8);
			$key = $this->aes_key;
			$sql = "update users set strong_pass = aes_encrypt(md5('$pass'), md5(join_date || '$key')) where ID = $user->ID";
			$db->execute($sql);
			mail($email, $subject, sprintf($body, $user->username, $pass, $login_url));
		}
		return $pass;
	}

	/**
	 * 
	 * @return the value of the property for the user
	 */
	private function __get($name)
	{
		if (isset($this->data->$name)) 
		{
			return $this->data->$name;
		}
		return '';
	}

	/**
	 * check if user is logged_in
	 * @return true if user is logged in.
	 */
	public function is_logged_in()
	{
		return $this->uid > 0;
	}

}
?>
