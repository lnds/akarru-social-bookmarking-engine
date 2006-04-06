<?php

include_once('akarru.lib/statistics.php');

class users {

	function users($db)
	{
		$this->db = $db;
		$psession = $_COOKIE["bm_login_cookie"];
		if (!isset($psession))
			$this->user = $_SESSION['user_data'];
		else
		{
			$this->user = $this->db->fetch_object("select * from users where persistent_session = '$psession'");
			$_SESSION['user_data'] = $this->user;
		}
	}

	function is_logged_in()
	{
		$psession = $_COOKIE["bm_login_cookie"];
		$pers_user = $this->db->fetch_object("select * from users where persistent_session = '$psession'");
		if ($pers_user->ID > 0)
		{
			$this->user = $pers_user;
		}
		else
		{
			$this->user = $_SESSION['user_data'];
		}
		return isset($this->user);
	}

	function logoff()
	{
		$psession = $_COOKIE["bm_login_cookie"];
		$this->db->execute("update users set persistent_session = null where persistent_session = '$psession'");
		unset($this->user);
		@session_destroy();
		@session_unset();
		$sessionid=session_name();
		setcookie ($sessionid, "", time()-3600);
		setcookie ("bm_login_cookie", '', time()-3600, '/', false);
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
		$now = time();
		$this->db->execute("insert into users(username,email,strong_pass,join_date) values('$user','$email',aes_encrypt(md5('$pass'), md5($now)), $now)");
		return $this->do_login($user, $pass, false);
	}


	function do_login($user_name, $pass, $remember)
	{
		$user_name = sanitize(strtolower($user_name));

		$user = $this->db->fetch_object("select ID, join_date from users where lower(username)=$user_name");
		if (empty($user->ID)) 
			return false;

		$user = $this->db->fetch_object("select * from users where lower(username)=$user_name and aes_decrypt(strong_pass, md5($user->join_date)) = md5('$pass')");
		if (!empty($user->ID))
		{
			$_SESSION['user_data'] = $user;
			if (!empty($remember))
			{
				$psession = md5($user->user_name . '-' . time());
				$uid = $user->ID;
				$this->db->execute("update users set persistent_session = '$psession' where ID = $uid");
				setcookie("bm_login_cookie", $psession, time()+86400*30, '/', false);
			}
			$this->user = $user;
			return true;
		}          
		return false;
	}

	function update_profile($data)
	{
		$sql  = ' update users set ';
		$sql .= ' email = '.sanitize($data['email']).',';
		$sql .= ' fullname= '.sanitize($data['fullname']).',';
		$sql .= ' website = '.sanitize($data['website']).',';
		$sql .= ' blog = '.sanitize($data['blog']).' ';
		$sql .= ' where ID = '.sanitize($data['user_id']);
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
		$sql  = ' select u.id, u.username, u.email, u.fullname, u.blog, u.join_date, u.website ';
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

		// gravatar
		$profile->gravatar = get_gravatar($bm_url, $profile->email, 80); 
		$profile->small_gravatar = get_gravatar($bm_url, $profile->email, 40);  


		return $profile;
	}

	function change_password($user_id, $new_pass, $confirm_pass)
	{
		if ($new_pass != $confirm_pass) {
			return false;
		}
		$sql = "update users set strong_pass = aes_encrypt(md5('$new_pass'), md5(join_date)) where ID = $user_id";
		$this->db->execute($sql);
		return true;
	}

	function gen_password($email, $subject, $body, $login_url)
	{
		$sql = " select * from users where email = '$email' limit 1";
		$user = $this->db->fetch_object($sql);
		if ($user) {
			$pass = substr(base64_encode(md5($user->password.$user->email.$user->id.time())), 0, 8);
			$sql = "update users set strong_pass = aes_encrypt(md5('$pass'), md5(join_date)) where ID = $user->ID";
			$this->db->execute($sql);
			mail($email, $subject, sprintf($body, $user->username, $pass, $login_url));
		}
		return $pass;
	}

	function check_email_exists($email)
	{
		return $this->db->fetch_scalar("select count(*) from users where email = '$email'");
	}

	function check_user_exists($username)
	{
		return $this->db->fetch_scalar("select count(*) from users where username = '$username'");
	}

	function get_random_profile_links($n)
	{
		$result = array();
		$users = $this->db->fetch("select username, email from users order by rand() limit $n");
		foreach ($users as $user)
		{
			$user->gravatar = get_gravatar($bm_url, $user->email, 40);
			$result[] = '<a href="profile.php?user_name='.$user->username.'"><img border="0" src="'.$user->gravatar.'" alt="'.$user->username.'" /></a>'
				.'<br/><a style="font-size:10px" href="profile.php?user_name='.$user->username.'">'.
				substr($user->username,0,10).'</a>';
		}
		return $result;
	}

	function get_profile_links($n)
	{
		$result = array();
		if ($n > 0) {
			$users = $this->db->fetch("select username, email from users u order by rand() limit $n");
		}
		else {
			$users = $this->db->fetch("select u.username, u.email from users u order by u.ID asc");
		}
		foreach ($users as $user)
		{
			$user->gravatar = get_gravatar($bm_url, $user->email, 40);
			$result[] = '<a href="profile.php?user_name='.$user->username.'"><img border="0" src="'.$user->gravatar.'" alt="'.$user->username.'" /></a>'
				.'<br/><a style="font-size:10px" href="profile.php?user_name='.$user->username.'">'.
				substr($user->username,0,10).'</a>';
		}
		return $result;
	}

}
?>
