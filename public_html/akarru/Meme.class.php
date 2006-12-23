<?

class Meme 
{
	var $id;
	var $tags;
	var $comments;

	public function __construct($id)
	{
		$this->id = intval($id);
	}

	public function load()
	{
		$db = Database::singleton();
		$this->data = $db->fetch_object('select p.*, pc.ID as cat_id, pc.cat_title, u.username, u.gravatar, u.ID as user_id from posts p join users u on u.id = p.submitted_user_id join post_cats pc on pc.id = p.category where p.ID = '.$this->id);
		$this->tags = $db->select_fields('t.tag', 'from tags t', 'where t.ID in (select tag_id from tags_posts where post_id = '.$this->id.')', '', 'limit 10');
		$this->comments = $db->select("from post_comments pc join users u on u.ID = pc.user_id ", 'where pc.post_id = '.$this->id);
		$this->data->micro_content = media_filter($this->data->url, 320, 280);
		return $this->data;
	}

	public function add_vote($uid)
	{
		$db = Database::singleton();
		$s = $this->check_votes($db, $uid);
		if ($s)
		{
			$votes = $db->fetch_scalar('select count(*) from post_votes where post_id = '.$this->id);
			return array('status'=>$s, 'uid'=>$uid, 'id'=>$this->id, 'votes'=>$votes);
		}
		return $this->do_vote($db, $uid);
	}

	public function do_vote($db, $uid)
	{
		$time = time();
		$ip = $_SERVER['REMOTE_ADDR'];
		$statement = $db->prepare('insert into post_votes (post_id,user_id,date_voted, ip_address) values (?,?,?,?)');
		$statement->bind_param('iiis', $this->id, $uid, $time, $ip);
		$statement->execute();
		$db->query('update posts set votes = votes + 1, promoted = (votes > 4) where ID = '.$this->id);
		$q = $db->query('select id, votes from posts where id = '.$this->id);
		return $q->fetch_assoc();
	}

	private function check_votes($db, $uid)
	{
		$s = $db->fetch_scalar('select count(*) from post_votes where user_id = '.intval($uid).' and post_id = '.intval($this->id));
		return $s;
	}

	 /**
	 * 
	 * @return the value of the property for the meme
	 */
	private function __get($name)
	{
		if (isset($this->data->$name)) {
			return $this->data->$name;
		}
		return '';
	}

	public static function check_exists_url($url)
	{
		if ($url == 'http://') 
			return false;
		if (empty($url)) 
			return false;

		$db = Database::singleton();
		$url = $db->sanitize($url);
		$found = $db->fetch_scalar("select count(*) from posts where url = '$url'");
		return $found > 0;
	}

	public static function create_meme($request)
	{
		$db = Database::singleton();
		$url = $db->sanitize($request['url']);
		$title = $db->sanitize($request['title']);
		$content_type = $db->sanitize($request['content_type']);
		$content = $db->sanitize($request['description']);
		$category = $db->sanitize($request['category']);
		$date_posted = time();
		$user_id = $_SESSION['user_id'];
		$sql  = 'insert into posts(title,content,date_posted,date_promo,category,url,submitted_user_id,trackback,is_micro_content,allows_debates)  ';
		$sql .= " values('$title','$content', $date_posted, $date_posted, $category, '$url', $user_id, '', $content_type, 1)";
		$db->execute($sql);
		$meme =  new Meme($db->insert_id);
		$meme->do_vote($db, $user_id);
		$meme->add_tags($request['tags'], $user_id);
		return $meme;
	}

	public function add_comment($comment, $user_id)
	{
		$db = Database::singleton();
		$comment = trim($db->sanitize($comment));
		if (empty($comment)) 
			return 0;
		$meme_id = $this->id;
		$date = time();
		$sql  = 'insert into post_comments(content,date_posted,user_id,post_id) ';
		$sql .= " values('$comment', $date, $user_id, $meme_id)";
		$db->execute($sql);
		$db->execute('update posts set comments = comments+1 where ID = '.$meme_id);
		return 1;
	}

	public function add_tags($tags, $user_id)
	{
		$db = Database::singleton();
		$tags = $db->sanitize($tags);
		$tag_array = split('[,]', $tags);
		$meme_id = $this->id;
		foreach ($tag_array as $tag)
		{
			$tag = trim($tag);
			if (!empty($tag)) 
			{
				$sql = "select ID from tags where tag = '$tag'";
				$tag_id = intval($db->fetch_scalar($sql));
				if ($tag_id == 0) 
				{
					$db->execute("insert into tags(tag) values('$tag')");
					$tag_id = $db->insert_id;
				}
				$sql = "insert into tags_posts(tag_id, post_id) values($tag_id, $meme_id)";
				@$db->execute($sql);
				$sql = "insert into tags_user(tag_id, user_id) values($tag_id, $user_id)";
				@$db->execute($sql);
			}
		}
	}
}


?>
