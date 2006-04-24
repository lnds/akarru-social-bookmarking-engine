<?php
include_once('lib/xmlrpc.inc');
include_once('lib/xmlrpcs.inc');

class memes {

	function memes($db, $user, $promo_level=7, $records_to_page=15)
	{
		$this->db = $db;
		$this->user_id = $user;
		$this->promote_threshold = $promo_level;
		$this->records_to_page = $records_to_page;
	}


	function get_voters($meme_id)
	{
		$meme_id = sanitize($meme_id);
		$sql = "select u.username, u.gravatar from users u join post_votes pv on pv.user_id = u.ID where pv.post_id = $meme_id ";
		$result = array();
		$rs = $this->db->get_recordset($sql);
		while ($user = $this->db->get_record_object($rs)) {
			$user->gravatar = get_gravatar($bm_url, $user->gravatar, 40);
			$result[] = '<a href="profile.php?user_name='.$user->username.'"><img border="0" src="'.$user->gravatar.'" alt="'.$user->username.'" /></a>'
				.'<br/><a style="font-size:10px" href="profile.php?user_name='.$user->username.'">'.substr($user->username, 0, 10).'</a>';
		}
		return $result;
	}


	function search_memes($term)
	{
		$sql="select p.*,pc.cat_title,u.username,u.gravatar, u.ID as user_id, pc.ID as cat_id from posts p join post_cats pc on pc.ID = p.category join users u where u.ID = p.submitted_user_id ";
		$sql.=" and (upper(p.title) like '%".strtoupper($term)."%' or upper(p.content) like '%".strtoupper($term)."%')";
		$sql.=" order by p.date_posted desc";

		return $this->filter_result($sql);
	}

	function get_category_name($cat_id)
	{
		$cat_id = sanitize($cat_id);
		return $this->db->fetch_scalar("select cat_title from post_cats where ID = $cat_id");
	}

	function get_tag_name($tag_id)
	{
		$tag_id = sanitize($tag_id);
		return $this->db->fetch_scalar("select tag from tags where ID = $tag_id");
	}

	function count_memes()
	{
		$this->db->do_query('select SQL_CALC_FOUND_ROWS * from posts p where p.promoted = 1'); 
		return $this->db->fetch_scalar('Select FOUND_ROWS()');

	}

	function get_memes($page=0, $sort='')
	{
		$memes_count = $this->count_memes();
		if ($memes_count <= 0) {
			return array();
		}
		
		$sql  = ' select p.*, pc.ID as cat_id, pc.cat_title, u.username, u.gravatar, u.ID as user_id ';
		$sql .= ' from posts p join users u on u.id = p.submitted_user_id  join post_cats pc on pc.id = p.category ';
		$sql .= ' where p.promoted = 1 ';

		$this->pages = ceil($memes_count / $this->records_to_page);
		if (!empty($sort)) 
		{
			$sql .= " $sort ";
		}
		else
			$sql.=" order by rank desc "; 

		if($page==0)
			$sql .= ' LIMIT '.$this->records_to_page; 
		else
		{ 
				$page--; 
				if ($page < 0) 
					$page = 0; 
				$sql.=" LIMIT ".($page*$this->records_to_page)."," . $this->records_to_page; 
		}
		return $this->filter_result($sql);
	}


	function get_meme($id)
	{
		$sql =  'select p.*, pc.cat_title, pc.ID as cat_id, u.username, u.gravatar, u.ID as user_id ';
		$sql .= 'from posts p join post_cats pc on pc.ID = p.category join users u on u.ID = p.submitted_user_id ';
		$sql .= "where p.ID = $id ";
		$meme = $this->db->fetch_object($sql);
		$meme->small_gravatar = get_gravatar($bm_url, $meme->email, 16); 
		if ($meme->is_micro_content == 2) 
			$meme->micro_content = get_youtube($meme->url);
		$uid = $this->user_id;
		if ($uid > 0) 
			$meme->voted = $this->db->fetch_scalar("select count(*) from post_votes where post_id = $id and user_id = $uid ");
		if ($meme->is_micro_content == 0)
			$meme->page_image = 'http://img.simpleapi.net/small/'.$meme->url;
		$meme->prior_meme = $this->db->fetch_object("select ID, title from posts where ID < $id order by ID desc limit 1");
		$meme->next_meme = $this->db->fetch_object("select ID, title from posts where ID > $id order by ID asc limit 1");
		return $meme;
	}

	function add_comment($meme_id, $comment)
	{
		$comment = trim($comment);
		if (empty($comment)) {
			return 0;
		}
		$comment = check_plain($comment);
		$user_id = $this->user_id;
		$date = time();
		$sql  = 'insert into post_comments(content,date_posted,user_id,post_id) ';
		$sql .= "values('$comment', $date, $user_id, $meme_id)";
		$this->db->execute($sql);
		$this->promote($meme_id, true, 1, 0);

	}

	function get_comments($meme_id)
	{
		$meme_id = sanitize($meme_id);
		$sql  = ' select c.title, c.content, c.date_posted, u.username, u.gravatar, c.post_id as ID ' ;
		$sql .= ' from post_comments c left join users u on c.user_id = u.ID ';
		$sql .= ' where post_id = '.$meme_id;
		return $this->filter_result($sql);
	}

	function get_tags($meme_id)
	{
		$meme_id = sanitize($meme_id);
		
		return $this->db->fetch("select t.tag, t.ID from tags t join tags_posts tp on t.ID = tp.tag_id where tp.post_id = $meme_id");
	}

	function get_new_memes($page=0, $sort='')
	{
		$this->db->do_query('select SQL_CALC_FOUND_ROWS * from posts p where p.votes < '.$this->promote_threshold); 
		$this->memes_count = $this->db->fetch_scalar('Select FOUND_ROWS()');
		if ($this->memes_count <= 0) {
			return array();
		}
		$sql =  'select p.*, pc.cat_title, pc.ID as cat_id, u.username, u.gravatar, u.ID as user_id ';
		$sql .= 'from posts p, post_cats pc, users u ';
		$sql .= 'where pc.ID=p.category and u.ID=p.submitted_user_id and p.votes < '.$this->promote_threshold;
		$this->pages = ceil($this->memes_count / $this->records_to_page);
		if (!empty($sort)) {
			$sql .= " $sort ";
		}
		else
		{
			$sql.=" order by p.date_posted desc "; 
		}
		if($page!=0){ 
			$page--; 
			if ($page < 0) 
				$page = 0; 
			$sql.=" LIMIT ".($page*$this->records_to_page)."," . $this->records_to_page; 
		}
		else 
		{ 
			$sql .= ' LIMIT '.$this->records_to_page; 
		}
		return $this->filter_result($sql);
	}

	function get_memes_by_user($user_id, $page=0, $sort='')
	{
		$sql =  'select p.*,pc.ID as cat_id, pc.cat_title,u.username,pc.ID as cat_id, u.gravatar, u.ID as user_id from posts p, post_cats pc, users u where pc.ID=p.category and u.ID=p.submitted_user_id ';
		$sql .= ' and u.ID = '.$user_id.' and votes >= '.$this->promote_threshold.' ';
		$this->memes_count = $this->db->count_rows($sql);
		$this->pages = ceil($this->memes_count / $this->records_to_page);
		if (!empty($sort)) {
			$sql .= " $sort ";
		}
		else
		{
			$sql.=" order by p.date_posted desc "; 
		}
		if($page!=0){ $page--; if ($page < 0) $page = 0; $sql.=" LIMIT ".($page*$this->records_to_page)."," . $this->records_to_page; }
		else { $sql .= ' LIMIT '.$this->records_to_page; }
		return $this->filter_result($sql);
	}


	function get_memes_by_category($cat_id, $page=0, $sort='', $limit=0)
	{
		$sql =  'select p.*, pc.ID as cat_id, pc.cat_title,u.username,u.ID as user_id, u.gravatar, pc.ID as cat_id from posts p, post_cats pc, users u  where pc.ID=p.category and u.ID=p.submitted_user_id ';
		$sql .= ' and pc.ID = '.$cat_id. ' and p.votes >= '.$this->promote_threshold.' ';
		$this->memes_count = $this->db->count_rows($sql);
		$this->pages = ceil($this->memes_count / $this->records_to_page);
		if (!empty($sort)) {
			$sql .= " $sort ";
		}
		else
		{
			$sql.=" order by p.date_posted desc "; 
		}
		if ($limit != 0) { $sql .= ' LIMIT '.$limit; }
		else if($page!=0){ $page--; if ($page < 0) $page = 0; $sql.=" LIMIT ".($page*$this->records_to_page)."," . $this->records_to_page; }
		else { $sql .= ' LIMIT '.$this->records_to_page; }
		return $this->filter_result($sql);
	}


	// calculate gravatar and if user voted for this meme
	function filter_result($sql)
	{
		$rs = $this->db->get_recordset($sql);
		$result = array();
		$counter = 0;
		while ($meme = $this->db->get_record_object($rs)) 
		{
			$meme->small_gravatar = get_gravatar($bm_url, $meme->gravatar, 24);
			$meme_id = $meme->ID;
			$uid = $this->user_id;
			if (!empty($uid) && !empty($meme_id)) {
				$meme->voted = $this->db->fetch_scalar("select count(*) from post_votes where post_id = $meme_id and user_id = $uid ");
			}
			$mc = $meme->is_micro_content;
			if ($mc == 2) 
				$meme->micro_content = get_youtube($meme->url);
			else if (!empty($meme->url))
				$meme->page_image = 'http://img.simpleapi.net/small/'.$meme->url;
			$meme->content = replace_urls($meme->content);
			$result[] = $meme;
			$counter++;
		}
		return $result;
	}


	function get_memes_by_tag($tag_id='',$page='', $sort=''){
		$sql = "select post_id from tags_posts where tag_id = $tag_id";
		$result = $this->db->fetch($sql);
		$post_ids = array();
		foreach ($result as $post)
		{
			array_push($post_ids, $post->post_id);
		}
		$in_set = implode(',', $post_ids);
		$sql="select p.*, pc.cat_title,u.username,u.gravatar, u.ID as user_id, pc.ID as cat_id from posts p, post_cats pc, users u where pc.ID=p.category and u.ID=p.submitted_user_id ";
		$sql .= " and p.ID in ($in_set) ";
		$this->memes_count = $this->db->count_rows($sql);
		$this->pages = ceil($this->memes_count / $this->records_to_page);
		if($page!=0){ $page--; if ($page < 0) $page = 0; $sql.=" LIMIT ".($page*$this->records_to_page)."," . $this->records_to_page; }
		else { $sql .= ' LIMIT '.$this->records_to_page; }
		return $this->filter_result($sql);
	}



	function get_votes($meme_id)
	{
		$sql="select votes from posts where ID=$meme_id";
		return $this->db->fetch_scalar($sql);

	}

	function check_votes_user($meme_id, $user_id)
	{
		$sql="select count(user_id) as vote_count from post_votes where user_id='$user_id' and post_id=$meme_id";
		$n = $this->db->fetch_scalar($sql);
		return $n > 0;
	}

	function vote_anon($meme_id)
	{
		$user_id = $this->user_id;
		$ip = $_SERVER['REMOTE_ADDR'];
		$sql = "select count(post_id) from post_votes where post_id = $meme_id and user_id = 0 and ip_address = '$ip'";
		$nv = $this->db->fetch_scalar($sql);
		if ($nv < 1 || empty($nv))
		{
			$this->vote($meme_id, 0, false);
			return true;
		}
		return false;
	}

	function vote($meme_id, $user_id, $promote=true)
	{
		$time = time();
		$ip = $_SERVER['REMOTE_ADDR'];
		$sql="insert into post_votes (post_id,user_id,date_voted, ip_address) values ($meme_id,$user_id,'$time', '$ip')";
		$this->db->execute($sql);
		$this->db->execute("update posts set votes = votes + 1 where ID = $meme_id");
		$this->promote($meme_id, $promote, 0, 0);
	}

	function check_post_url($url)
	{
		if (!$this->check_is_valid_url($url)) {
			return 2;
		}
		elseif ($this->check_url_exists_in_db($url) != 0) {
			return 1;
		}
		return 0;
	}


	function check_is_valid_url($url)
	{
		$furl = @fopen($url, "r");
		if (!$furl) {
			return false;
		}
		@fclose($furl);
		return true;

	}

	function check_url_exists_in_db($url)
	{
		$url = trim($url);
		$sql = "select count(id) url_exist from posts where url = '$url'";
		return $this->db->fetch_scalar($sql);
	}


	function add_meme($data)
	{
		$title = check_plain($data['title']);
		$content = check_plain($data['content_body']);
		$category = $data['category'];
		$url = check_plain($data['url']);
		$user_id = $data['user_id'];
		$date_posted = time();
		$trackback = $data['meme_trackback'];
		$content_type = $data['content_type'];
		$allows_debates = $data['debates'] == 1 ? 1 : 0;
		$sql  = 'insert into posts(title,content,date_posted,date_promo,category,url,submitted_user_id,trackback,is_micro_content, allows_debates) ';
		$sql .= " values('$title','$content',$date_posted,$date_posted,$category,'$url',$user_id,'$trackback', $content_type, $allows_debates)";
		if ($this->db->execute($sql))
		{
			$meme_id = $this->db->insert_id;
			$tags = $data['meme_tags'];
			$this->post_tags($meme_id, $user_id, $tags);
			if (!empty($trackback))
			{
				$data['meme_id'] = $meme_id;
				$this->send_trackback($data);
			}
			$this->vote($meme_id, $user_id, true);
			$this->ping('ping.feedburner.com', 'weblogUpdates.ping', $meme_id);
			//$this->ping('ping.bitacoras.com', 'weblogUpdates.ping', $meme_id);

		}

	}



	function update_meme($data)
	{
		$title = check_plain($data['title']);
		$content = check_plain($data['content_body']);
		$category = $data['category'];
		$url = $data['url'];
		$user_id = $data['user_id'];
		$date_posted = time();
		$content_type = $data['content_type'];
		$meme_id = $data['meme_id'];
		$allows_debates = isset($data['debates']) ? 1 : 0;
		$sql  = "update posts set title='$title',content='$content',category=$category,url='$url',is_micro_content=$content_type,allows_debates=$allows_debates where ID = $meme_id";
		if ($this->db->execute($sql))
		{
			$tags = $data['meme_tags'];
			$this->post_tags($meme_id, $user_id, $tags);
	  //  	$this->ping('ping.feedburner.com', 'weblogUpdates.ping', $meme_id);
	//		$this->ping('ping.bitacoras.com', 'weblogUpdates.ping', $meme_id);
		}
		return true;
	}


	function ping($ping_host, $ping_app, $meme_id, $title='Blog Memes', $main_url='http://www.blogmemes.com/')
	{
		$blogMemes = new xmlrpc_client('', $ping_host, 80);
		$msg = new xmlrpcmsg($ping_app, array(new xmlrpcval($title), new xmlrpcval()));
		$doPing = $blogMemes->send($msg);
		return ($doPing && $doPing->faultCode() == 0);
	}


	function post_tags($meme_id, $user_id, $tags)
	{
		$tags = split('[,]',$tags);
		foreach ($tags as $tag)
		{
			$tag = trim($tag);
			if (!empty($tag)) 
			{
				$sql = "select ID from tags where tag = '$tag'";
				$row = $this->db->fetch($sql);
				$tag_id =  $row[0]->ID;
				if (empty($tag_id))
				{
					$sql = "insert into tags(tag) values('$tag')";
					if ($this->db->execute($sql))
					{
						$tag_id = $this->db->insert_id;
						$sql = "insert into tags_posts(tag_id, post_id) values($tag_id, $meme_id)";
						$this->db->execute($sql);
					}
				}
				else
				{
					$sql = "select tag_id, post_id from tags_posts where tag_id = $tag_id and post_id = $meme_id";
					$row = $this->db->fetch($sql);

					if (empty($row)) 
					{
						$sql = "insert into tags_posts(tag_id, post_id) values($tag_id, $meme_id)";
						$this->db->execute($sql);
					}
				}
				$sql = "select tag_id,user_id from tags_user where tag_id = $tag_id and user_id = $user_id";
				$row = $this->db->fetch($sql);
				$tag_id2 =  $row[0]->tag_id;
				$user_id2 = $row[0]->user_id;
				if ($tag_id != $tag_id2 || $user_id != $user_id2)
				{
					$sql = "insert into tags_user(tag_id, user_id) values($tag_id, $user_id)";
					$this->db->execute($sql);
				}
			}
		}
		$this->promote($meme_id, true, 0, 0);

	}


	// the promotion algorithm moves memes to the front of the queue
	function promote($meme_id, $update_promo_date=false, $add_comments = 0, $add_votes = 0)
	{
		$meme = $this->get_meme($meme_id);

		$nv = $meme->votes + $add_votes;
		$nc = $meme->comments + $add_comments;
		$now = time();
		$hours_posted = ceil(($now - $meme->date_posted)/3600);
		$hours_promoted = ceil(($now - $meme->date_promo)/3600);
		if ($hours_promoted < 0) 
			$hours_promoted = 1;
		if ($hour_posted < 0) 
			$hours_posted = 1;
		$rank = 1000 + log10($meme->votes+$meme->comments+$meme->clicks+$meme->debate_pos+$meme->debate_neg+$meme->debate_0+10);
		$rank *=  1/(1+log10(10+$hours_posted*1000));
 	$rank *=  10/(1+log10(10+$hours_promoted));
//		$rank = ceil($rank*log10(100*$meme->votes+10));

		if ($update_promo_date)
			$sql = "update posts set rank = $rank, date_promo = $now";
		else 
			$sql = "update posts set rank = $rank";
		if ($add_votes)
			$sql .= ", votes = $nv";
		if ($add_comments)
			$sql .= ", comments = $nc ";
		if ($nv >= $this->promote_threshold)
		{
			$sql .= ', promoted = 1 ';
		}
		$sql .= " where ID = $meme_id";

		$this->db->execute($sql);

	}

	function click($meme_id, $user_id)
	{
		$this->db->execute("update posts set clicks = clicks+1 where ID = $meme_id");
		$this->promote($meme_id, false, 0, 0);
		$this->debate($meme_id, $user_id, 0, true);
	}

    function send_trackback($data) {


        $title = urlencode($data['title']);
        $excerpt = urlencode($data['content_body']);
        $blog_name = urlencode('blogmemes.com');
        $tb_url = $data['meme_trackback'];
        $url = urlencode($this->get_permalink($data['meme_id']));
        $query_string = "charset=UTF-8&title=$title&url=$url&blog_name=$blog_name&excerpt=$excerpt";
        $trackback_url = @parse_url($tb_url);
        $http_request  = 'POST ' . $trackback_url['path'] . ($trackback_url['query'] ? '?'.$trackback_url['query'] : '') . " HTTP/1.0\r\n";
        $http_request .= 'Host: '.$trackback_url['host']."\r\n";
        $http_request .= 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8'."\r\n";
        $http_request .= 'Content-Length: '.strlen($query_string)."\r\n";
        $http_request .= "User-Agent: AKARRU (http://www.blogmemes.com) ";
        $http_request .= "\r\n\r\n";
        $http_request .= $query_string;
		$http_request = utf8_encode($http_request);
        if ( '' == $trackback_url['port'] )
                $trackback_url['port'] = 80;
        $fs = @fsockopen($trackback_url['host'], $trackback_url['port'], $errno, $errstr, 5);
		if($fs && ($res=@fputs($fs, $http_request)) ) {
        	@fclose($fs);
			return true;	
		}
	    return false;
	}

    function get_permalink($id) {
		return "http://www.blogmemes.com/comment.php?meme_id=$id";
	}

	function get_category($cat_id)
	{
		return $this->db->fetch_object("select cat_title, feed from post_cats where ID = $cat_id");
	}

									 
	function promote_all()
	{
		$sql = 'select ID from posts ';
		$rs = $this->db->get_recordset($sql);
		while ($meme = $this->db->get_record_object($rs)) {

			$nv = $this->db->fetch_scalar("select count(*) from post_votes where post_id = ".$meme->ID);
			$nc = $this->db->fetch_scalar("select count(*) from post_comments where post_id = ".$meme->ID);
			$this->db->execute("update posts set comments = $nc, votes = $nv where ID = ".$meme->ID);
			$this->promote($meme->ID);
			if (connection_aborted()) {
				break;
			}
			$meme_id = $meme->ID;
			$this->db->execute("update posts set debate_0 = (select count(*) from debate where position = 0 and post_id = $meme_id) where ID = $meme_id");
			$this->db->execute("update posts set debate_pos = (select count(*) from debate where position > 0 and post_id = $meme_id) where ID = $meme_id");
			$this->db->execute("update posts set debate_neg = (select count(*) from debate where position < 0 and post_id = $meme_id) where ID = $meme_id");

		}
		return;
	}

	function get_debate_users($meme_id, $position)
	{
		if ($position == 0) {
			$users = $this->db->fetch('select d.ID, u.gravatar, u.username from users u join debate d on d.user_id = u.ID where d.position = '.$position." and d.post_id = $meme_id ");
		}
		else if ($position > 0) {
			$users = $this->db->fetch('select d.ID, u.gravatar, u.username from users u join debate d on d.user_id = u.ID where d.position > 0 and d.post_id = '.$meme_id);
		}
		else
		{
			$users = $this->db->fetch('select d.ID, u.gravatar, u.username from users u join debate d on d.user_id = u.ID where d.position < 0 and d.post_id = '.$meme_id);

		}
		$result =  array();
		foreach ($users as $user)
		{
			$user->gravatar = get_gravatar($bm_url, $user->gravatar, 40);
			$result[] = '<a href="profile.php?user_name='.$user->username.'"><img border="0" src="'.$user->gravatar.'" alt="'.$user->username.'" /></a>'
				.'<br/><a style="font-size:10px" href="profile.php?user_name='.$user->username.'">'.substr($user->username,0,10).'</a>';
		}
		return $result;

	}

	function get_friends($meme_id)
	{
		return $this->get_debate_users($meme_id, 1);
	}

	function get_foes($meme_id)
	{
		return $this->get_debate_users($meme_id, -1);
	}

	function get_neutrals($meme_id)
	{
		return $this->get_debate_users($meme_id, 0);
	}

	
	function debate($meme_id, $user_id, $pos, $check_exists = true)
	{   
		if (empty($user_id))
			return;
		$posobj = $this->db->fetch_object("select id, position from debate where post_id = $meme_id and user_id = $user_id");
		if (empty($posobj)) {
			$this->db->execute("insert into debate(position,post_id,user_id) values($pos, $meme_id, $user_id)");
		}
		else 
		{
			if (!$check_exists) {
				$this->db->execute("update debate set position = $pos where id = ".$posobj->id);
			}
		}
		if ($pos != 0 && !$this->check_votes_user($meme_id,$user_id))
		{
				$this->vote($meme_id, $user_id, 1);
		}
		$this->db->execute("update posts set debate_0 = (select count(*) from debate where position = 0 and post_id = $meme_id) where ID = $meme_id");
		$this->db->execute("update posts set debate_pos = (select count(*) from debate where position > 0 and post_id = $meme_id) where ID = $meme_id");
		$this->db->execute("update posts set debate_neg = (select count(*) from debate where position < 0 and post_id = $meme_id) where ID = $meme_id");

	}

}

?>
