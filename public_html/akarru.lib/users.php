<?php

include_once('akarru.lib/statistics.php');

class users {

	function users($db, $aes_key=AES_KEY)
	{
		$this->aes_key = $aes_key;
		$this->db = $db;
		if (isset($_SESSION['user_data']))
            $this->user = $_SESSION['user_data'];
        
		if (empty($this->user)) {
			$psession = isset($_COOKIE["bm_login_cookie"]) ? $_COOKIE["bm_login_cookie"] : '';
			$this->user = $this->db->fetch_object("select * from users where persistent_session = '$psession' and not (disabled=TRUE and validated=TRUE) LIMIT 1");
			$_SESSION['user_data'] = $this->user;
		}
	}

	function is_logged_in()
	{
		$this->user = $_SESSION['user_data'];
		return !empty($this->user);
	}

	function is_valid_account()
    {
        $valid = false;
        if (isset($this->user)) {
            // Need to check the database to avoid the situation where the user has been banned
            // but still have a valid session going on
            $id = $this->user->ID;
             $exists  = $this->db->fetch_scalar("select count(*) from users where
 ID = '$id'  and (disabled=false and validated=true) LIMIT 1");
            $valid = $exists > 0;            
		}
        
        return $valid;
    }

	function is_banned()
    {
        $banned = false;
        if (isset($this->user)) {
            // Need to check the database to avoid the situation where the user has been banned
            // but still have a valid session going on
            $id = $this->user->ID;
             $exists  = $this->db->fetch_scalar("select count(*) from users where
 ID = '$id'  and (disabled=TRUE and validated=TRUE) LIMIT 1");
            $banned = $exists > 0;
		}
        
        return $banned;
    }

	function logoff($domain=DOMAIN)
	{
		if (isset($_COOKIE["bm_login_cookie"]))
        {
		    $psession = $_COOKIE["bm_login_cookie"];
		    $this->db->execute("update users set persistent_session = null where persistent_session = '$psession'");
        }
        else if (isset($this->user))
        {
            $this->db->execute("update users set persistent_session = null where ID = '$this->user->ID'");
        }
        
        unset($this->user);
		
		@session_destroy();
		@session_unset();
		$sessionid=session_name();
		setcookie ($sessionid, "", time()-3600);
		setcookie ("bm_login_cookie", '', time()-3600, '/', $domain);
		return 1;
	}

	function count_users()
	{
		return $this->db->fetch_scalar('select count(*) from users');
	}

	function get_user_id()
	{
		return $this->user->ID;
	}

	function get_is_admin()
	{
		if ($this->is_logged_in() && isset($this->user))
        {
		    return $this->user->ID == 1 || $this->user->admin == 1;
        }
        else
        {
            return false;
        }
	}


	function get_user_name()
	{
		return isset($this->user) ? $this->user->username : '';
	}


	function get_user_email()
	{
		return isset($this->user) ? $this->user->email : '';
	}
    
    function get_user_url()
	{
		if (isset($this->user))
        {
            if (strlen($this->user->website))
            {
                return $this->user->website;
            }
            else
            {
                return $this->user->blog;
            }
        }
        else
        {
            return '';
        }
	}

	function get_user_gravatar()
	{
		return isset($this->user) ? $this->user->gravatar : '';
	}


	function register_user($user, $email, $pass)
	{
		if ($this->check_user_exists($user)) 
			return FALSE;
		
		if ($this->check_email_exists($email)) 
			return FALSE;
		
		$user = htmlspecialchars($user);
		if (empty($user)) {
			return FALSE;
		}
		$email = htmlspecialchars($email);
		if (empty($email)) {
			return FALSE;
		}

		$now = time();
		$key = $this->aes_key;
		$this->db->execute("insert into users(username,email,strong_pass,join_date,gravatar,disabled,validated) values('$user','$email', aes_encrypt(md5('$pass'), md5($now || '$key')), $now, md5('$email'), TRUE, FALSE)");

		return $this->do_login($user, $pass, false);
	}

	function validate_user($user_id)
	{
        $user_id = sanitize($user_id);
        $sql  = "update users set validated=TRUE, disabled=FALSE where ID = $user_id";
		return $this->db->execute($sql);
	}
    
    function invalidate_user($user_id)
	{
        $user_id = sanitize($user_id);
        $sql  = "update users set validated=FALSE, disabled=TRUE where ID = $user_id";
		return $this->db->execute($sql);
	}
    
    function disable_user($user_id)
    {
        $user_id = sanitize($user_id);
        $sql  = "update users set disabled=TRUE where ID = $user_id";
		return $this->db->execute($sql);
    }
    
    function enable_user($user_id)
    {
        $user_id = sanitize($user_id);
        $sql  = "update users set disabled=FALSE where ID = $user_id";
		return $this->db->execute($sql);
    }
    
    function ban_user($user_id)
    {
        $user_id = sanitize($user_id);
        $sql  = "update users set disabled=TRUE, validated=TRUE, persistent_session = null where ID = $user_id";
		return $this->db->execute($sql);
    }
    
    function unban_user($user_id)
    {
       $user_id = sanitize($user_id);
       $sql  = "update users set disabled=FALSE, validated=TRUE where ID = $user_id";
       return $this->db->execute($sql);
    }
    
    function is_user_banned($username)
    {
        $username = sanitize($username);
        $exists  = $this->db->fetch_scalar("select count(*) from users where
 username = $username  and (disabled=TRUE and validated=TRUE) LIMIT 1");
        $banned = $exists > 0;
	  
        return $banned;
    }
    

	function do_login($user_name, $pass, $remember, $domain=DOMAIN)
	{
		$user_name = sanitize(strtolower($user_name));
        // search for user_name, ignore banned account (disabled and validated)
		$user = $this->db->fetch_object("select ID, join_date from users where lower(username)=$user_name and not (disabled=true and validated=true)");
		if (empty($user->ID))
        {
			return false;
        }
		$key = $this->aes_key;
		$user = $this->db->fetch_object("select * from users where lower(username)=$user_name and aes_decrypt(strong_pass, md5(join_date || '$key')) = md5('$pass')");

		if (empty($user->ID))
			return false;
		$_SESSION['user_data'] = $user;
		if (!empty($remember))
		{
			$psession = md5($user->username . '-' . time());
			$uid = $user->ID;
			$this->db->execute("update users set persistent_session = '$psession' where ID = $uid");
			setcookie("bm_login_cookie", $psession, time()+24*60*60*30, '/', $domain);
		}
		$this->user = $user;
		return 1;
	}

	function update_profile($data)
	{
		$oldEmail = $this->get_user_email();
		$sql  = ' update users set ';
		$sql .= ' email = '.sanitize($data['email']).',';
		$sql .= ' gravatar = md5('.sanitize($data['email']).'), ';
		$sql .= ' fullname= '.sanitize($data['fullname']).',';
		$sql .= ' website = '.sanitize($data['website']).',';
		$sql .= ' blog = '.sanitize($data['blog']).' ';
		$sql .= ' where ID = '.sanitize($this->get_user_id());
		$this->db->execute($sql);


		$this->user->email = $data['email'];
		$this->user->fullname = $data['fullname'];
		$this->user->website = $data['website'];
		$this->user->blog = $data['blog'];
		$_SESSION['user_data'] = $this->user;

        if (strcmp($oldEmail, $data['email']) != 0)
        {
            $this->invalidate_user($this->user->ID);
            $this->sendValidationLink($this->user->username, $this->user->email);
        }
        
		return true;
	}

	function edit_user($data)
	{
		$oldEmail = $this->get_user_email();
		$sql  = ' update users set ';
		$sql .= ' email = '.sanitize($data['email']).',';
		$sql .= ' gravatar = md5('.sanitize($data['email']).'), ';
		$sql .= ' fullname= '.sanitize($data['fullname']).',';
		$sql .= ' website = '.sanitize($data['website']).',';
		$sql .= ' blog = '.sanitize($data['blog']).' ';
		$sql .= ' where ID = '.sanitize($data['user_id']);
		$this->db->execute($sql);

		return true;
	}

	function get_user_profile($user_name, $memes, $promote_level = 5)
	{   
		$id = $this->db->fetch_scalar(" select id from users where username = '$user_name' ");
		return $this->get_user_profile_by_id($id, $memes, $promote_level);
	}

    function get_user_id_by_user_name($user_name)
	{
        if (!$this->check_user_exists($user_name)) 
			return 0;
		$id = $this->db->fetch_scalar(" select id from users where username = '$user_name' ");
		return $id;
	}


	function get_user_profile_by_id($user_id, $memes, $promote_level = 5)
	{
		$sql  = ' select u.id, u.username, u.gravatar, u.fullname, u.blog, u.join_date, u.website ';
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

		$user_memes = $memes->get_memes_by_user($profile->id);
		$profile->promoted_memes = $user_memes->memes_count;

		// gravatar
		$profile->small_gravatar = get_gravatar($profile->gravatar, 40);
		$profile->gravatar = get_gravatar($profile->gravatar, 80);


		return $profile;
	}

	function get_user_by_id($user_id)
	{
		$sql  = ' select id, username, email, gravatar, fullname, blog, join_date, website, disabled, validated ';
		$sql .= ' from users';
		$sql .= " where id = $user_id ";
		$user_data = $this->db->fetch_object($sql);
		// gravatar
		$user_data->small_gravatar = get_gravatar($user_data->gravatar, 40);
		$user_data->gravatar = get_gravatar($user_data->gravatar, 80);


		return $user_data;
	}

	function change_password($user_id, $new_pass, $confirm_pass)
	{
		$user_id = sanitize($user_id);
		if ($new_pass != $confirm_pass) 
			return FALSE;
		$key = $this->aes_key;
		$this->db->execute("update users set strong_pass = aes_encrypt(md5('$new_pass'), md5(join_date || '$key')) where ID = $user_id");
		return true;
	}

	function gen_password($email, $subject, $body, $login_url)
	{
		global $bm_domain;
		$user = $this->db->fetch_object("select * from users where email = '$email' limit 1");
		if ($user) 
		{
			$pass = mb_substr(base64_encode(md5($user->password.$user->email.$user->id.time())), 0, 8);
			$key = $this->aes_key;
			$sql = "update users set strong_pass = aes_encrypt(md5('$pass'), md5(join_date || '$key')) where ID = $user->ID";
			$this->db->execute($sql);
			$from = "no-reply@" . $bm_domain;
            $header = 'From: '.$from."\n";
            mb_send_mail($email, $subject, sprintf($body, $user->username, $pass, $login_url), $header);
		}
		return $pass;
	}

	function check_email_exists($email)
	{
		return $this->db->fetch_scalar("select count(*) from users where email = '$email' LIMIT 1");
	}

	function check_user_exists($username)
	{
		return $this->db->fetch_scalar("select count(*) from users where username = '$username' LIMIT 1");
	}

	function get_random_profile_links($n)
	{
		$result = array();
		$users = $this->db->fetch("select username, gravatar from users order by rand() limit $n");
		foreach ($users as $user)
		{
			$user->gravatar = get_gravatar($user->gravatar, 40);
			$result[] = '<a href="/user/'.$user->username.'"><img border="0" src="'.$user->gravatar.'" alt="'.$user->username.'" /></a>'
				.'<br/><a style="font-size:10px" href="/user/'.$user->username.'">'.
				mb_substr($user->username,0,10,"UTF-8").'</a>';
		}
		return $result;
	}

	function get_profile_links($n,$page=0,$page_size=240)
	{
		$result = array();
		if ($n > 0) {
			$users = $this->db->fetch("select username, gravatar from users u order rand() limit $n");

		}
		else {
		$users = $this->db->fetch("select username, gravatar from users u order by ID asc limit ".(($page-1)*$page_size).', '.$page_size);
		}
		foreach ($users as $user)
		{
			$user->gravatar = get_gravatar($user->gravatar, 40);
			$result[] = '<a href="/user/'.$user->username.'"><img border="0" src="'.$user->gravatar.'" alt="'.$user->username.'" /></a>'
				.'<br/><a style="font-size:10px" href="/user/'.$user->username.'">'.
			   mb_substr($user->username,0,10,"UTF-8").'</a>';
		}
		return $result;
	}
    
    function makeRandomKey($minlength, $maxlength)
    {
        $charset = "abcdefghijklmnopqrstuvwxyz";
        $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $charset .= "0123456789";
        $key = "";
        if ($minlength > $maxlength)
        	$length = mt_rand ($maxlength, $minlength);
        else
        	$length = mt_rand ($minlength, $maxlength);
        for ($i=0; $i<$length; $i++)
        	$key .= $charset[(mt_rand(0,(strlen($charset)-1)))];
        return $key;
    }
    
    function makeValidationLink($user, $user_email, $code)
    {
        global $bm_url;
        $hash_value = md5($user . $user_email);
        $user_id = $this->get_user_id_by_user_name($user);
        $validationLink = $bm_url . "validate_user.php?h=" . $hash_value . "&k=" . $code;
        $validationFile = $_SERVER["DOCUMENT_ROOT"] . "/cache/validations/" . $hash_value;
        $handleValidation = fopen($validationFile, "w");
        if ($handleValidation)
        {
            fwrite($handleValidation, $code . "\n" . $user_id . "\n" . $user . "\n" . $user_email);
            fclose($handleValidation);
        }
       
        return $validationLink;    
    }
    
    function sendValidationLink($user, $user_email)
    {
        global $bm_site_name;
        global $bl_validate_user;
        global $bf_validation_link_email_body;
        global $bm_domain;
        $code = $this->makeRandomKey(8,12);
        $validationLink = $this->makeValidationLink($user, $user_email, $code);
        // Configure these
        $from="no-reply@" . $bm_domain;   // Sender's adress
        $subject = '['.$bm_site_name.'] '.$user . ":" .$bl_validate_user;
        $body = $bf_validation_link_email_body;
        $header = 'From: '.$from."\n";
        return mb_send_mail($user_email, $subject, sprintf($body, $user, $validationLink, $code), $header);
    }
    
    function verifyValidationLink($hash_value, $key)
    {
        global $smarty;
        $valid = false;
        $validHash = false;
        $validKey = false;
    
        if (! empty($hash_value))
        {
            $validHash = ctype_alnum($hash_value);
        }
        
        if (! empty($key))
        {
            $validKey = ctype_alnum($key);
        }
        
        if ($validHash && $validKey)
        {
            $validationFile = $_SERVER["DOCUMENT_ROOT"] . "/cache/validations/" . $hash_value;
            $validationFileExists = file_exists($validationFile);
            $validKey = false;
            if ($validationFileExists)
            {
                $strings = array_map('rtrim',file($validationFile));
                $validKey = strcmp($strings[0], $key) == 0;
                $user_id_value = $strings[1];
                $user_name_value = $strings[2];
                $email_value = $strings[3];

                unlink($validationFile);
                
                $valid = $validKey;
                
                if ($valid)
                {                    
                    if ($user_id_value > 0)
                    {
                        $this->validate_user($user_id_value);
                        $smarty->clear_cache(null,'db|users|profile|' . $user_name_value);
                    }
                    else
                    {
                        $valid = false;
                    }
                }
            }
        }
        
        return $valid;
    }
    
    function verifyValidationKey($key)
    {
        if (! $this->is_logged_in())
        {
            return false;
        }
        
        $this->user->username;
        $this->user->email;
        $hash = md5($this->user->username . $this->user->email);
        return $this->verifyValidationLink($hash, $key);
    }

}
?>
