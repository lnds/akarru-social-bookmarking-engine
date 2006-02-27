<?php

include_once('akarru.lib/statistics.php');

class users {

	function users($db)
	{
		$this->db = $db;
		$this->user = $_SESSION['user_data'];
	}

	function is_logged_in(){
		$this->user = $_SESSION['user_data'];
		return isset($this->user);
	}

	function logoff(){
		@session_destroy();
		@session_unset();
		$sessionid=session_name();
		setcookie ($sessionid, "", time()-3600);
		return true;
	}


	function get_user_id()
	{
		if (isset($this->user)) 
			return $this->user->ID;
		return '';
	}

	function get_is_admin()
	{
		if (isset($this->users)) {
			return $this->users->admin == 1;
		}
		return false;
	}


	function get_user_name()
	{
		if (isset($this->user)) 
			return $this->user->username;
		return '';
	}

	function register_user($user, $email, $pass)
	{
		if ($this->check_user_exists($user)) {
			return false;
		}
		if ($this->check_email_exists($email)) {
			return false;
		}
		$user = htmlspecialchars($user);
		$email = htmlspecialchars($email);
		$md_pass = md5($pass);
		$now = time();
		$this->db->execute("insert into users(username,email,password,join_date) values('$user','$email','$md_pass', $now)");
		return $this->do_login($user, $pass);
	}


	function do_login($user, $pass)
	{
		$user = $this->db->fetch_object("select ID, username, password, email,join_date,admin,website,blog,fullname from users where lower(username)='".strtolower($user)."'");
		if ($user->password == md5($pass)) 
		{
			$_SESSION['user_data'] = $user;
			$this->user = $user;
			return true;
		}          
		return false;
	}

	function update_profile($data)
	{
		$sql  = ' update users set ';
		$sql .= ' email = \''.$data['email'].'\',';
		$sql .= ' fullname= \''.$data['fullname'].'\',';
		$sql .= ' website = \''.$data['website'].'\',';
		$sql .= ' blog = \''.$data['blog'].'\' ';
		$sql .= ' where ID = '.$data['user_id'];
		$this->db->execute($sql);

		$this->user->email = $data['email'];
		$this->user->fullname = $data['fullname'];
		$this->user->website = $data['website'];
		$this->user->blog = $data['blog'];
		$_SESSION['user_data'] = $this->user;
		return true;
	}

	function get_user_profile($user_name, $memes, $promote_level = 5)
	{
		$sql  = " select id from users where username = '$user_name' ";
		$id = $this->db->fetch_scalar($sql);
		return $this->get_user_profile_by_id($id, $memes, $promote_level);
	}


	function get_user_profile_by_id($user_id, $memes, $promote_level = 5)
	{
		$sql  = ' select u.id, u.username, u.fullname, u.blog, u.join_date, u.website ';
		$sql .= ' from users u left join posts p on p.submitted_user_id = u.id ';
		$sql .= " where u.id = $user_id ";
		$profile = $this->db->fetch_object($sql);
		$stats = new statistics($this->db);
		$infl  = $stats->calc_influence($profile->id);
		$profile->memes = $infl->memes;
		$profile->votes = $infl->votes;
		$profile->comments = $infl->comments;
		$profile->influence = $infl->influence;
		$profile->popularity = $infl->popularity;
		$profile->memes_votes = $infl->memes_votes;

		$memes->get_memes_by_user($profile->id);
		$profile->promoted_memes = $memes->memes_count;

		return $profile;
	}

	function change_password($user_id, $new_pass, $confirm_pass)
	{
		if ($new_pass != $confirm_pass) {
			return false;
		}
		$sql = 'update users set password = \''.md5($new_pass).'\' where ID = '.$user_id;
 	$this->db->execute($sql);

		return true;
	}

	function gen_password($email, $subject, $body, $login_url)
	{
		srand(time());
		$sql = " select * from users where email = '$email' limit 1";
		$user = $this->db->fetch_object($sql);
		if ($user) {
			$pass = substr(base64_encode(md5($user->password.$user->email.$user->id.time())), 0, 8);
			$sql = 'update users set password = \''.md5($pass).'\'  where ID = '.$user->ID;
			$this->db->execute($sql);
			mail($email, $subject, sprintf($body, $user->username, $pass, $login_url));
		}
		return $pass;
	}

	function check_email_exists($email)
	{
		$sql = 'select count(*) from users where email = \''.$email.'\'';
		return $this->db->fetch_scalar($sql);
	}

	function check_user_exists($username)
	{
		$sql = 'select count(*) from users where username = \''.$username.'\'';
		return $this->db->fetch_scalar($sql);
	}

}
?>
