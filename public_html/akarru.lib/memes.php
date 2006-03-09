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
		$sql = "select u.username, u.email from users u left join post_votes pv on pv.user_id = u.ID where pv.post_id = $meme_id ";
		return $this->filter_result($sql);
	}

	function search_memes($term){
		$sql="select p.title,p.content,p.date_posted,p.clicks, p.category,p.rank,p.url,p.submitted_user_id,p.ID,pc.cat_title,u.username,pc.ID as cat_id, p.votes as vote_count, count(pcom.ID) as comment_count from posts p, post_cats pc, users u left join post_comments pcom on pcom.post_id=p.ID where pc.ID=p.category and u.ID=p.submitted_user_id ";
		$sql.=" and (upper(p.title) like '%".strtoupper($term)."%' or upper(p.content) like '%".strtoupper($term)."%')";
		$sql.=" group by p.title,p.content,p.date_posted,p.category,p.url,p.submitted_user_id,p.ID,pc.cat_title,u.username ";
		$sql.=" order by p.date_posted desc";

		return $this->filter_result($sql);
	}

	function get_category_name($cat_id)
	{
		$sql = "select cat_title from post_cats where ID = $cat_id";
		return $this->db->fetch_scalar($sql);
	}

	function get_tag_name($tag_id)
	{
		$sql = "select tag from tags where ID = $tag_id";
		return $this->db->fetch_scalar($sql);
	}

	function get_memes($page=0, $sort='', $limit=0, $show_sql=0)
	{
		$this->memes_count = $this->db->fetch_scalar('select count(*) n from posts p where p.votes >= '.$this->promote_threshold);
		if ($this->memes_count <= 0) {
			return array();
		}
		
		
		$sql =  'select p.title,p.is_micro_content,p.content,p.date_posted,p.clicks,p.category,p.url,p.submitted_user_id,p.ID,p.rank, ';
		$sql .= 'pc.cat_title,u.username, u.email, pc.ID as cat_id,votes as vote_count, count(pcom.ID) as comment_count ';
		$sql .= 'from posts p, post_cats pc, users u left join post_comments pcom on pcom.post_id=p.ID ';
		$sql .= 'where pc.ID=p.category and u.ID=p.submitted_user_id and p.votes >= '.$this->promote_threshold;
		$sql .= " group by p.title,p.content,p.date_posted,p.category,p.url,p.submitted_user_id,p.ID,pc.cat_title,u.username ";

		$this->pages = ceil($this->memes_count / $this->records_to_page);
		if (!empty($sort)) {
			$sql .= " $sort ";
		}
		else
		{
			$sql.=" order by rank desc, vote_count desc "; 
		}
		if($page!=0)
		{ 
				$page--; 
				if ($page < 0) 
					$page = 0; 
				$sql.=" LIMIT ".($page*$this->records_to_page)."," . $this->records_to_page; }
		else 
		{
			if ($limit > 0)  { $sql .= ' LIMIT '.$limit; }
			else { $sql .= ' LIMIT '.$this->records_to_page; }
		}
		return $this->filter_result($sql);
	}


	function get_meme($id)
	{
		$sql =  'select p.title,p.content,p.date_posted,p.date_promo,p.rank,p.clicks,p.category,p.url,p.submitted_user_id,p.ID,pc.cat_title,u.username, u.email, pc.ID as cat_id, p.votes as vote_count, count(pcom.ID) as comment_count from posts p, post_cats pc, users u left join post_comments pcom on pcom.post_id=p.ID where pc.ID=p.category and u.ID=p.submitted_user_id ';
		$sql .= ' and p.ID='.$id;
		$sql .= " group by p.title,p.content,p.date_posted,p.category,p.url,p.submitted_user_id,p.ID,pc.cat_title,u.username ";
		$result = $this->db->fetch_object($sql);
		$result->small_gravatar = get_gravatar($bm_url, $comment->email, 16); 
		return $result;
	}

	function add_comment($meme_id, $comment)
	{
		$comment = check_plain($comment);
		$user_id = $this->user_id;
		$date = time();
		$sql  = 'insert into post_comments(content,date_posted,user_id,post_id) ';
		$sql .= "values('$comment', $date, $user_id, $meme_id)";
		$this->db->execute($sql);
		$this->promote($meme_id, true);
		$this->ping_technorati($meme_id);
		$this->ping_feedburner($meme_id);
		$this->ping_bitacoras($meme_id);

	}

	function get_comments($meme_id)
	{
		$sql  = ' select c.title, c.content, c.date_posted, u.username, u.email ';
		$sql .= ' from post_comments c left join users u on c.user_id = u.ID ';
		$sql .= ' where post_id = '.$meme_id;
		return $this->filter_result($sql);
	}

	function get_tags($meme_id)
	{
		$sql = 'select t.tag, t.ID from tags t join tags_posts tp on t.ID = tp.tag_id where tp.post_id = '.$meme_id;
		return $this->db->fetch($sql);
	}

	function get_new_memes($page=0, $sort='')
	{
		$sql =  'select p.title,p.is_micro_content,p.content,p.date_posted,p.rank,p.clicks,p.category,p.url,p.submitted_user_id,p.ID, ';
		$sql .=  'pc.cat_title,u.username, u.email, pc.ID as cat_id, p.votes as vote_count ';
		$sql .= ' from posts p, post_cats pc, users u where pc.ID=p.category and u.ID=p.submitted_user_id ';
		$sql .= ' and p.votes < '.$this->promote_threshold.' ';
		$this->memes_count = $this->db->count_rows($sql);
		$this->pages = ceil($this->memes_count / $this->records_to_page);
		if ($this->records_to_page <= 0 ) {
			$this->records_to_page = 15;
		}
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

	function get_memes_by_user($user_id, $page=0, $sort='')
	{
		$sql =  'select p.title,p.is_micro_content,p.content,p.date_posted,p.category,p.url,p.submitted_user_id,p.ID,pc.cat_title,u.username,pc.ID as cat_id, p.votes as vote_count, count(pcom.ID) as comment_count from posts p, post_cats pc, users u left join post_comments pcom on pcom.post_id=p.ID where pc.ID=p.category and u.ID=p.submitted_user_id ';
		$sql .= ' and u.ID = '.$user_id.' ';
		$sql .= " group by p.title,p.content,p.date_posted,p.category,p.url,p.submitted_user_id,p.ID,pc.cat_title,u.username ";
		$sql .= ' having vote_count >= '.$this->promote_threshold.' ';
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
		$sql =  'select p.title,p.is_micro_content,p.content,p.rank,p.clicks,p.date_posted,p.category,p.url,p.submitted_user_id,p.ID,pc.cat_title,u.username,pc.ID as cat_id, p.votes as vote_count, count(pcom.ID) as comment_count from posts p, post_cats pc, users u left join post_comments pcom on pcom.post_id=p.ID where pc.ID=p.category and u.ID=p.submitted_user_id ';
		$sql .= ' and pc.ID = '.$cat_id.' ';
		$sql .= " group by p.title,p.content,p.date_posted,p.category,p.url,p.submitted_user_id,p.ID,pc.cat_title,u.username ";
		$sql .= ' having vote_count >= '.$this->promote_threshold.' ';
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

	function filter_result($sql)
	{
		$data = $this->db->fetch($sql);
		$result = array();
		
		foreach ($data as $meme)
		{
			$meme->small_gravatar = get_gravatar($bm_url, $meme->email, 16);
			$result[] = $meme;
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
		$sql="select p.title,p.is_micro_content,p.content,p.date_posted,p.category,p.url,p.submitted_user_id,p.ID,pc.cat_title,u.username,pc.ID as cat_id, p.votes as vote_count, count(pcom.ID) as comment_count from posts p, post_cats pc, users u left join post_comments pcom on pcom.post_id=p.ID where pc.ID=p.category and u.ID=p.submitted_user_id ";
		$sql .= " and p.ID in ($in_set) ";
		$sql.=" group by p.title,p.content,p.date_posted,p.category,p.url,p.submitted_user_id,p.ID,pc.cat_title,u.username, pc.ID ";
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
		$this->promote($meme_id, $promote);
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
		$url = $data['url'];
		$user_id = $data['user_id'];
		$date_posted = time();
		$trackback = $data['meme_trackback'];
		$sql  = 'insert into posts(title,content,date_posted,date_promo,category,url,submitted_user_id,trackback) ';
		$sql .= "values('$title','$content',$date_posted,$date_posted,$category,'$url',$user_id,'$trackback')";
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
			$this->ping_technorati($meme_id);
			$this->ping_feedburner($meme_id);
			$this->ping_bitacoras($meme_id);
			$this->ping_blogalaxia($meme_id);
		}

	}

	function ping_blogalaxia($meme_id)
	{
		$blogMemes = new xmlrpc_client("/ping.php?blogID=23565", "www.blogalaxia.com", 80);
		$msg = new xmlrpcmsg("weblogUpdates.ping", 
				array(new xmlrpcval("Nuevos Blog Memes"), 
					  new xmlrpcval("http://www.blogmemes.com/memes_queue.php")
					 )
			);
		$doPing = $blogMemes->send($msg);
		return ($doPing && $doPing->faultCode() == 0);
		
	}

	function ping_technorati($meme_id) 
	{
		$blogMemes = new xmlrpc_client("/rpc/ping", "rpc.technorati.com", 80);
		$msg = new xmlrpcmsg("weblogUpdates.ping", 
				array(new xmlrpcval("Blog Memes"), 
					  new xmlrpcval("http://www.blogmemes.com/")
					 )
			);
		$doPing = $blogMemes->send($msg);
		return ($doPing && $doPing->faultCode() == 0);
	}


	function ping_feedburner($meme_id) 
	{
		$blogMemes = new xmlrpc_client("", "ping.feedburner.com", 80);
		$msg = new xmlrpcmsg("weblogUpdates.ping", array(new xmlrpcval("Blog Memes"), new xmlrpcval("http://www.blogmemes.com/")));
		$doPing = $blogMemes->send($msg);
		return ($doPing && $doPing->faultCode() == 0);
	}


	function ping_bitacoras($meme_id) 
	{
		$blogMemes = new xmlrpc_client("", "ping.bitacoras.com", 80);
		$msg = new xmlrpcmsg("weblogUpdates.ping", array(new xmlrpcval("Blog Memes"), new xmlrpcval("http://www.blogmemes.com/")));
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
		$this->promote($meme_id, true);

	}


	// the promotion algorithm moves memes to the front of the queue
	function promote($meme_id, $update_promo_date=false)
	{
		$meme = $this->get_meme($meme_id);
		$sql = "select votes from posts where ID = $meme_id ";
		$nv = $this->db->fetch_scalar($sql);
		$now = time();
		$hours_posted = ceil(($now - $meme->date_posted)/3600);
		$hours_promoted = ceil(($now - $meme->date_promo)/3600);
		if ($hours_promoted < 0) {
			$hours_promoted = 1;
		}
		if ($hour_posted < 0) {
			$hours_posted = 1;
		}
		$delta = ($hours_posted - $hours_promoted);
		if ($delta < 0) {
			$delta = -$delta;
		}
		$rank = 1000 + log10($meme->vote_count+$meme->comment_count+$meme->clicks+$hours_promoted+10);
		$rank *=  1/(1+log10(10+$hours_posted));
		$rank *=  10/(1+log10(10+$hours_promoted));
		$rank = ceil($rank*log10(10*$meme->vote_count+10));

		if ($update_promo_date) {
			$sql = "update posts set rank = $rank, date_promo = $now where ID = $meme_id";
		}
		else {
			$sql = "update posts set rank = $rank where ID = $meme_id";
		}
		$this->db->execute($sql);

	}

	function click($meme_id)
	{
		$this->db->execute("update posts set clicks = clicks+1 where ID = $meme_id");
		$this->promote($meme_id, true);
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
		$sql = 'select p.ID from posts p join post_votes pv on pv.post_id = p.ID group by p.ID';
		$memes = $this->db->fetch($sql);
		foreach ($memes as $meme) {
			$this->promote($meme->ID);
		}
		return;
	}
}


?>
