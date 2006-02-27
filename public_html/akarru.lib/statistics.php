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
		$sql  = ' select u.username, u.ID, count(distinct (p.ID)) memes, count(distinct (v.ID)) votes, count(distinct(c.ID)) comments ';
		$sql .= ' from users u, posts p left join post_votes v on v.user_id = u.ID left join post_comments c on c.user_id = u.ID ';
		$sql .= " where (p.submitted_user_id = u.ID) and (u.ID = $user_id) ";
		$sql .= ' group by u.username, u.ID ';
		$u =  $this->db->fetch_object($sql);
		$u->votes = $this->db->fetch_scalar("select count(*) votes from post_votes where user_id = $user_id");
		$u->influence = 100.0*(($u->votes-$u->memes+$u->comments+1)/($memes+1));
		$sql = 'select count(distinct (v.ID)) pop, count(distinct(v.ID)) meme_votes from post_votes v, posts p where v.post_id = p.ID and p.submitted_user_id = '.$user_id;
		$v = $this->db->fetch_object($sql);
		$u->popularity = $u->memes * log10($v->pop+10);
		$u->memes = $u->memes + 0;
		$u->memes_votes = $v->meme_votes + 0;
		$u->comments += 0;
		return $u;
	}


	function top_posters($limit = 25)
	{
		$memes = $this->count_memes()+1;
		$sql  = ' select u.username, u.ID, count(distinct (p.ID)) memes, count(distinct (v.ID)) votes, count(distinct(c.ID)) comments, ';
		$sql .= " 100.0*((count(distinct (v.ID)) - count(distinct(p.ID)) + count(distinct(c.ID))+1)/$memes) as rank ";
		$sql .= ' from users u, posts p left join post_votes v on v.user_id = u.ID left join post_comments c on c.user_id = u.ID ';
		$sql .= ' where (p.submitted_user_id = u.ID) and (u.ID > 1) ';
		$sql .= ' group by u.username, u.ID order by rank desc limit '.$limit;
		$users = $this->db->fetch($sql);
		foreach ($users as $u)
		{
			$sql = 'select count(distinct (v.ID) pop from post_votes v, posts p where v.post_id = p.ID and p.submitted_user_id = '.$u->ID;
			$u->popularity = $u->memes * log10($this->db->fetch_scalar($sql)+10);
			$result[] = $u;
			
		}
		return $result;
		
	}

}
?>
