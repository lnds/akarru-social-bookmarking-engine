<?php

include_once('akarru.lib/statistics.php');

class users {

	function users($db, $aes_key=AES_KEY)
	{
		$this->aes_key = $aes_key;
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
		if (isset($psession)) {
			$pers_user = $this->db->fetch_object("select * from users where persistent_session = '$psession'");
			if ($pers_user->ID > 0)
				$this->user = $pers_user;
		}
		else
			$this->user = $_SESSION['user_data'];
		return !empty($this->user);
	}

	function logoff($domain=DOMAIN)
	{
		$psession = $_COOKIE["bm_login_cookie"];
		$this->db->execute("update users set persistent_session = null where persistent_session = '$psession'");
		unset($this->user);
		@session_destroy();
		@session_unset();
		$sessionid=session_name();
		setcookie ($sessionid, "", time()-3600);
		setcookie ("bm_login_cookie", '', time()-3600, '/', $domain);
		return true;
	}


	function get_user_id()
	{
		return $this->user->ID;
	}

	function get_is_admin()
	{
		return $this->users->admin == 1;
	}


	function get_user_name()
	{
		return isset($this->user) ? $this->user->username : '';
	}

	function register_user($user, $email, $pass)
	{
		if ($this->check_user_exists($user)) 
			return FALSE;
		
		if ($this->check_email_exists($email)) 
			return FALSE;
		
		$user = htmlspecialchars($user);
		$email = htmlspecialchars($email);
		$now = time();
		$key = $this->aes_key;
		$this->db->execute("insert into users(username,email,strong_pass,join_date,gravatar) values('$user','$email', aes_encrypt(md5('$pass'), md5($now || '$key')), $now, md5('$email'))");

		return $this->do_login($user, $pass, false);
	}


	function do_login($user_name, $pass, $remember, $domain=DOMAIN)
	{
		$user_name = sanitize(strtolower($user_name));
		$user = $this->db->fetch_object("select ID, join_date from users where lower(username)=$user_name");
		if (empty($user->ID)) 
			return false;
		$key = $this->aes_key;
		$user = $this->db->fetch_object("select * from users where lower(username)=$user_name and aes_decrypt(strong_pass, md5(join_date || '$key')) = md5('$pass')");
		if (empty($user->ID))
			return false;
		$_SESSION['user_data'] = $user;
		if (!empty($remember))
		{
			$psession = md5($user->user_name . '-' . time());
			$uid = $user->ID;
			$this->db->execute("update users set persistent_session = '$psession' where ID = $uid");
			setcookie("bm_login_cookie", $psession, time()+24*60*60*30, '/', $domain);
		}
		$this->user = $user;
		return true;
	}

	function update_profile($data)
	{
		$sql  = ' update users set ';
		$sql .= ' email = '.sanitize($data['email']).',';
		$sql .= ' gravatar = md5('.sanitize($data['email']).'), ';
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
		$id = $this->db->fetch_scalar(" select id from users where username = '$user_name' ");
		return $this->get_user_profile_by_id($id, $memes, $promote_level);
	}


	function get_user_profile_by_id($user_id, $memes, $promote_level = 5)
	{
		$sql  = ' select u.id, u.username, u.gravatar, u.fullname, u.blog, u.join_date, u.website ';
		$sql .= ' from users u left join posts p on p.submitted_user_id = u.id ';
		$sql .= " where u.id = $user_id ";
		$profile = $this->db->fetch_object($sql);
		$stats = new statistics($this->db);
	   // $infl  = $stats->calc_influence($profile->id);
		$profile->memes = $infl->memes;
		$profile->votes = $infl->votes;
		$profile->comments = $infl->comments;
		$profile->influence = $infl->influence;
		$profile->popularity = $infl->popularity;
		$profile->memes_votes = $infl->memes_votes;

		$memes->get_memes_by_user($profile->id);
		$profile->promoted_memes = $memes->memes_count;

		// gravatar
		$profile->small_gravatar = get_gravatar($bm_url, $profile->gravatar, 40);
		$profile->gravatar = get_gravatar($bm_url, $profile->gravatar, 80);


		return $profile;
	}

	function change_password($user_id, $new_pass, $confirm_pass)
	{
		if ($new_pass != $confirm_pass) 
			return FALSE;
		$key = $this->aes_key;
		$this->db->execute("update users set strong_pass = aes_encrypt(md5('$new_pass'), md5(join_date || '$key')) where ID = $user_id");
		return true;
	}

	function gen_password($email, $subject, $body, $login_url)
	{
		$user = $this->db->fetch_object("select * from users where email = '$email' limit 1");
		if ($user) 
		{
			$pass = substr(base64_encode(md5($user->password.$user->email.$user->id.time())), 0, 8);
			$key = $this->aes_key;
			$sql = "update users set strong_pass = aes_encrypt(md5('$pass'), md5(join_date || '$key')) where ID = $user->ID";
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
		$users = $this->db->fetch("select username, gravatar from users order by rand() limit $n");
		foreach ($users as $user)
		{
			$user->gravatar = get_gravatar($bm_url, $user->gravatar, 40);
			$result[] = '<a href="profile.php?user_name='.$user->username.'"><img border="0" src="'.$user->gravatar.'" alt="'.$user->username.'" /></a>'
				.'<br/><a style="font-size:10px" href="profile.php?user_name='.$user->username.'">'.
				substr($user->username,0,10).'</a>';
		}
		return $result;
	}

	function get_profile_links($n)
	{
		$result = array();
		if ($n > 0) 
			$users = $this->db->fetch("select username, gravatar from users u order by rand() limit $n");
		else 
			$users = $this->db->fetch("select u.username, u.gravatar from users u order by u.ID asc");
		foreach ($users as $user)
		{
			$user->gravatar = get_gravatar($bm_url, $user->gravatar, 40);
			$result[] = '<a href="profile.php?user_name='.$user->username.'"><img border="0" src="'.$user->gravatar.'" alt="'.$user->username.'" /></a>'
				.'<br/><a style="font-size:10px" href="profile.php?user_name='.$user->username.'">'.
				substr($user->username,0,10).'</a>';
		}
		return $result;
	}

}
?>
