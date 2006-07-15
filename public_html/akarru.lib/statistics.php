<?php

class poster {
	function post($username, $memes)
	{
		$this->username = $username;
		$this->memes = $memes;
	}
	var $username;
	var $votes = 0;
	var $memes = 0;
	var $comments = 0;
	var $rank = 0;
}

class statistics {

	function statistics($db)
	{	
		$this->db = $db;
	}

	function count_users()
	{
		return $this->db->fetch_scalar("select count(*) from users");
	}

	function count_clicks()
	{
		return $this->db->fetch_scalar("select sum(clicks) from posts");
	}

	function count_social_clicks()
	{
		return $this->db->fetch_scalar("select sum(social_clicks) from posts");
	}

	function count_memes()
	{
		return $this->db->fetch_scalar('select count(*) from posts');
	}

	function count_votes()
	{
		return $this->db->fetch_scalar('select count(*) from post_votes');
	}

	function count_comments()
	{
		return $this->db->fetch_scalar('select count(*) from post_comments');
	}

	function calc_influence($user_id)
	{
		$memes = $this->count_memes();
		$sql  = " select submitted_user_id, count(p.id) memes, sum(votes) memes_votes, sum(comments) received_comments ";
        $sql .= " from posts p ";
        $sql .= " where (p.submitted_user_id = $user_id) ";
        $sql .= " group by submitted_user_id";
		$u = $this->db->fetch_object($sql);
		$u->comments = $this->db->fetch_scalar("select count(*) from post_comments where user_id = $user_id");
		$u->votes = $this->db->fetch_scalar("select count(*) from post_votes where user_id = $user_id");
		if (!isset($u->memes))
        {
         $u->memes = 0;
        }
        
        if (!isset($u->memes_votes))
        {
         $u->memes_votes = 0;
        }
        
        if (!isset($u->received_comments))
        {
         $u->received_comments = 0;
        }
        
        if (!isset($u->comments))
        {
            $u->comments = 0;
        }
        
        if (!isset($u->votes))
        {
            $u->votes = 0;
        }
		
		$u->popularity = ($u->memes+1) * log($u->memes_votes+$u->received_comments+1);
        $u->influence = 100.0*(($u->votes-$u->memes+$u->comments+1)/($memes+1));
		return $u;
	}



	function top_posters($limit = 10)
	{
		$memes = $this->count_memes();
		$sql  = ' select u.username, u.ID, u.gravatar, count(p.ID) memes ';
		$sql .= ' from users u join posts p on p.submitted_user_id = u.ID ';
		$sql .= ' group by u.username, u.ID, u.gravatar order by memes desc limit '.$limit;
		$users = $this->db->fetch($sql);
		foreach ($users as $u)
		{
			$user_id = $u->ID;
			$u->small_gravatar = get_gravatar($u->gravatar, 40);
			$sql  = " select submitted_user_id, sum(votes) memes_votes, sum(comments) received_comments from posts p where (p.submitted_user_id = $u->ID) group by submitted_user_id";
			$uf = $this->db->fetch_object($sql);
			$u->memes_votes = $uf->memes_votes;
			$u->received_comments = $uf->received_comments;
			$u->comments = $this->db->fetch_scalar("select count(*) from post_comments where user_id = $user_id");
			$u->votes = $this->db->fetch_scalar("select count(*) from post_votes where user_id = $user_id");
			$u->popularity = $u->memes+1 * log($u->memes_votes+$u->received_comments+1);
            $u->influence = 100.0*(($u->votes-$u->memes+$u->comments+1)/($memes+1));
			$result[] = $u;
			
		}
		return $result;
		
	}

}
?>
