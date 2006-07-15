<?php
include_once('lib/xmlrpc.inc');
include_once('lib/xmlrpcs.inc');

class memes {

	function memes($db, $user, $promo_level=5, $records_to_page=15)
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
			$user->gravatar = get_gravatar($user->gravatar, 40);
			$result[] = '<a href="/user/'.$user->username.'"><img border="0" src="'.$user->gravatar.'" alt="'.$user->username.'" /></a>'
				.'<br/><a style="font-size:10px" href="/user/'.$user->username.'">'.mb_substr($user->username, 0, 10, "UTF-8").'</a>';
		}
		return $result;
	}


	function search_memes($term, $page=0, $sort='')
	{
		$term = sanitize('%'.strtoupper($term).'%');
		return $this->get_memes($page, $sort, 1, " (upper(p.title) like $term or upper(p.content) like $term)");
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

	function get_tag_id($tag_name)
	{
		$tag_name = sanitize($tag_name);
		return $this->db->fetch_scalar("select ID from tags where tag = $tag_name limit 1");
	}

	function count_memes($promoted=1, $conditions='')
	{
		if (empty($conditions)) 
			$this->db->do_query("select SQL_CALC_FOUND_ROWS * from posts p where p.promoted = $promoted"); 
		else
			$this->db->do_query("select SQL_CALC_FOUND_ROWS * from posts p where $conditions ");

		return $this->db->fetch_scalar('Select FOUND_ROWS()');

	}

	function get_video_memes($page)
	{
		return $this->get_memes($page, 'order by views desc', 0, 'is_micro_content = 2');
	}

	function get_memes($page=0, $sort='', $promoted=1, $conditions='')
	{
		$memes_count = $this->count_memes($promoted, $conditions);
		if ($memes_count <= 0) {
			$this->pages = 0;
			return array();
		}
		
		$sql  = ' select p.*, pc.ID as cat_id, pc.cat_title, u.username, u.gravatar, u.ID as user_id ';
		$sql .= ' from posts p join users u on u.id = p.submitted_user_id  join post_cats pc on pc.id = p.category ';
		if (empty($conditions)) {
			$sql .= " where p.promoted = $promoted ";
		}
		else
		{
			$sql .= " where $conditions ";
		}

        $sql .= 'and p.disabled = FALSE';
        
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

    function get_original_meme($id)
	{
		global $bm_url;
		$sql =  'select p.*, pc.cat_title, pc.ID as cat_id, u.username, u.gravatar, u.ID as user_id ';
		$sql .= 'from posts p join post_cats pc on pc.ID = p.category join users u on u.ID = p.submitted_user_id ';
		$sql .= "where p.ID = $id ";
		$meme = $this->db->fetch_object($sql);
		$meme->small_gravatar = get_gravatar($meme->gravatar, 24); 
		$uid = $this->user_id;
		if ($uid > 0) 
			$meme->voted = $this->db->fetch_scalar("select count(*) from post_votes where post_id = $id and user_id = $uid ");
		if ($meme->is_micro_content == 0 && !empty($meme->url))
			$meme->page_image = 'http://img.simpleapi.net/small/'.$meme->url;
		else if ($meme->is_micro_content == 2) 
			$meme->micro_content = get_youtube($meme->url);

		$meme->prior_meme = $this->db->fetch_object("select ID, title from posts where ID < $id order by ID desc limit 1");
		$meme->next_meme = $this->db->fetch_object("select ID, title from posts where ID > $id order by ID asc limit 1");
		$meme->sociable_link = $bm_url . 'share/'.$meme->ID;
		$meme->sociable_title = 'via blogmemes: '.$meme->title;
		$meme->permalink = $this->get_permalink($meme->ID);
		$meme->sharelink = $this->get_sharelink($meme->ID);

		$meme->next_meme->permalink = $this->get_permalink($meme->next_meme->ID); 
		$meme->next_meme->sharelink = $this->get_sharelink($meme->next_meme->ID);

		$meme->prior_meme->permalink = $this->get_permalink($meme->prior_meme->ID); 
		$meme->prior_meme->sharelink = $this->get_sharelink($meme->prior_meme->ID);
        
        return $meme;
	}

	function get_meme($id, $share=0)
	{
        $id = sanitize($id);
		$meme = $this->get_original_meme($id);
        $meme->content = replace_urls($meme->content);

		if ($share)
	        $this->db->execute('update posts set shares = shares+1 where ID = '.$id);
		else
	 		$this->db->execute('update posts set views = views + 1 where ID = '.$id);
		return $meme;
	}
    
    function get_raw_meme($id)
	{
        $id = sanitize($id);
		$meme = $this->get_original_meme($id);
        $meme->content = remove_urls($meme->content);

		return $meme;
	}

    function disable_meme($meme_id)
    {
        $meme_id = sanitize($meme_id);
        $sql  = "update posts set disabled=TRUE where ID = $meme_id";
		return ($this->db->execute($sql));
    }
    
    function enable_meme($meme_id)
    {
        $meme_id = sanitize($meme_id);
        $sql  = "update posts set disabled=FALSE where ID = $meme_id";
		return ($this->db->execute($sql));
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

	function get_commented_memes_by_user($page, $user_id)
	{
		return $this->get_memes($page, 'order by date_posted desc', 0, 'p.ID in (select post_id from post_comments where user_id = '.$user_id. ' )');
	}

	function get_voted_memes_by_user($page, $user_id)
	{
		return $this->get_memes($page, 'order by date_posted desc', 0, 'p.ID in (select post_id from post_votes where user_id = '.$user_id. ' )');
	}


	function get_tags($meme_id, $limit = 0)
	{
		$meme_id = sanitize($meme_id);

		if (intval($limit) > 0) 
			$limit = 'LIMIT '.$limit;
		else
			$limit = '';
		return $this->db->fetch("select t.tag, t.ID from tags t join tags_posts tp on t.ID = tp.tag_id where tp.post_id = $meme_id $limit");
	}

	function get_new_memes($page=0, $sort='order by date_posted desc')
	{
		return $this->get_memes($page, $sort, 0);
	}

	function get_memes_by_user($user_id, $page=0, $sort='order by date_posted desc')
	{
		return $this->get_memes($page, $sort, 1, "p.submitted_user_id = $user_id");
	}


	function get_memes_by_category($cat_id, $page=0, $sort='order by date_posted desc', $limit=0)
	{                                                           
		return $this->get_memes($page, $sort, 1, " p.category = $cat_id ");
	}


	// calculate gravatar and if user voted for this meme
	function filter_result($sql)
	{
		global $bm_url;
		$rs = $this->db->get_recordset($sql);
		$result = array();
		$counter = 0;
		while ($meme = $this->db->get_record_object($rs)) 
		{
			$meme->small_gravatar = get_gravatar($meme->gravatar, 24);
			$meme_id = $meme->ID;
			$uid = $this->user_id;
			if (!empty($uid) && !empty($meme_id)) {
				$meme->voted = $this->db->fetch_scalar("select count(*) from post_votes where post_id = $meme_id and user_id = $uid ");
			}
			$mc = $meme->is_micro_content;
			if (empty($meme->url)) {
				$meme->url = "/meme/$meme->ID";
			}
			else
			{
				if ($mc == 2) 
					$meme->micro_content = get_youtube($meme->url);
				else 
					$meme->page_image = 'http://img.simpleapi.net/small/'.$meme->url;
			}
			$meme->content = replace_urls($meme->content);
			$meme->sociable_link = $bm_url . 'share/'.$meme->ID;
			$meme->sociable_title = 'via blogmemes: '.$meme->title;
			$meme->permalink = $this->get_permalink($meme->ID);
			$meme->sharelink = $this->get_sharelink($meme->ID);
			$result[] = $meme;
			$counter++;
			$this->db->execute('update posts set views = views + 1 where ID = '.$meme->ID);
		}
		$result->memes_count = $counter;
		return $result;
	}


	function get_memes_by_tag($tag_id,$page=0, $sort='order by date_posted desc')
	{
		$sql = "select post_id from tags_posts where tag_id = $tag_id";
		$result = $this->db->fetch($sql);
		$post_ids = array();
		foreach ($result as $post)
		{
			array_push($post_ids, $post->post_id);
		}
		$in_set = implode(',', $post_ids);
		return $this->get_memes($page, $sort, 0, " p.ID in ($in_set) ");
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
		$ip = $_SERVER['REMOTE_ADDR'];
		$sql = "select count(post_id) from post_votes where post_id = $meme_id and user_id = 0 and ip_address = '$ip'";
		$nv = intval($this->db->fetch_scalar($sql));
		if ($nv < 1)
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
        $sql = "select id from posts where url = '$url'";
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
		$trackback = check_plain($data['meme_trackback']);
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
				$this->send_trackback($data, $meme_id);
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
				$sql = "select ID from tags where tag = '$tag' LIMIT 1";
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
		$meme = $this->db->fetch_object('select * from posts where ID = '.$meme_id);
		$nv = $meme->votes + $add_votes;
		$nc = $meme->comments + $add_comments;
		$now = time();
		$hours_posted = ceil(($now - $meme->date_posted)/3600);
		$hours_promoted = ceil(($now - $meme->date_promo)/3600);
        if ($hours_promoted < 0) 
			$hours_promoted = 1;
		if ($hours_posted < 0) 
			$hours_posted = 1;
		$rank = 1000 + log10($meme->votes+$meme->comments+$meme->clicks+$meme->social_clicks+$meme->debate_pos+$meme->debate_neg+$meme->debate_0+10);
		$rank *=  1/(1+log10(10+$hours_posted*1000));
 		$rank *=  10/(1+log10(10+$hours_promoted));

		if ($update_promo_date)
			$sql = "update posts set rank = $rank, date_promo = $now";
		else 
			$sql = "update posts set rank = $rank";
		if ($add_votes)
			$sql .= ", votes = $nv";
		if ($add_comments)
			$sql .= ", comments = $nc ";
		if ($nv+$nc >= $this->promote_threshold)
		{
			$sql .= ', promoted = 1 ';
		}
		$sql .= " where ID = $meme_id";

		$this->db->execute($sql);

        // The cache for the different gadgets might need to be refreshed
        processCache($meme_id);
	}

	function click($meme_id, $user_id)
	{
		$this->db->execute("update posts set clicks = clicks+1 where ID = $meme_id");
		$this->promote($meme_id, false, 0, 0);
		$this->debate($meme_id, $user_id, 0, true);
		return $this->db->fetch_scalar("select url from posts where ID = $meme_id");
	}

    // To count the click on the social tools: bookmarks and send a link
    // For bookmarks, we can not be sure that the link was really saved
    // For the send a link we are sure that the link was sent
    function social_click($meme_id, $user_id)
	{
		$this->db->execute("update posts set social_clicks = social_clicks+1 where ID = $meme_id");
		$this->promote($meme_id, false, 0, 0);
		$this->debate($meme_id, $user_id, 0, true);
	}

    function send_trackback($data) {
        // This is to include a "Liked what you just read here ? Vote for it on Blogmemes !"
        // kind of message at the beginning of the excerpt of the trackback
        global  $bl_vote_for_it_message_in_trackback;

        $title = urlencode($data['title']);
        $excerpt = urlencode($bl_vote_for_it_message_in_trackback . $data['content_body']);
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

    function get_permalink($id) 
	{
        global $bm_url;
		return $bm_url . "meme/$id";
	}

    function get_sharelink($id) {
        global $bm_url;
		return $bm_url . "share/$id";
	}

	function get_category($cat_id)
	{
		return $this->db->fetch_object("select cat_title, feed from post_cats where ID = $cat_id and disabled = false");
	}

	function get_category_id($cat_name)
	{
		$cat_name = sanitize($cat_name);
		return $this->db->fetch_scalar("select ID from post_cats where cat_title = $cat_name limit 1");
	}

	function promote_all()
	{
		$sql = 'select id from posts ';
		$rs = $this->db->get_recordset($sql);
		while ($meme = $this->db->get_record_object($rs)) {
			$this->promote($meme->id);
			if (connection_aborted()) {
				break;
			}
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
			$result[] = '<a href="/user/'.$user->username.'"><img border="0" src="'.$user->gravatar.'" alt="'.$user->username.'" /></a>'
				.'<br/><a style="font-size:10px" href="/user/'.$user->username.'">'.mb_substr($user->username,0,10,"UTF-8").'</a>';
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
