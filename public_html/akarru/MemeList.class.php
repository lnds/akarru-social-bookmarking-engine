<?

class MemeList {

	var $page_size = 15;
	var $content_type = 0;
	var $page = 1;
	var $pages = 0;

	public function __construct($page, $published='where promoted = true', $micro_content='0', $order_by='order by date_posted desc')
	{
		$db = Database::singleton();
		$this->content_type = $micro_content;
		$this->page = $page;
		$this->order = $order_by;
		$this->load_published($db, $published);
	}

	private function load_published($db, $where)
	{
		$this->size = $this->count_memes($db, $where);
		$this->rows = $this->load_page($db, $this->page, $where);
		$this->pages = ceil($this->size / $this->page_size);
	}


	public function get_memes()
	{
		return $this->rows;
	}

	private function load_page($db, $page, $where)
	{
		$low = ($page-1)*$this->page_size;
		$result = $db->select_fields('p.*, pc.ID as cat_id, pc.cat_title, u.username, u.gravatar, u.ID as user_id', 'from posts p join users u on u.id = p.submitted_user_id join post_cats pc on pc.id = p.category', $where, $this->order, "limit $low, $this->page_size" );
		return $this->filter($db, $result);
	}

	private function filter($db, $data)
	{
		$result = array();
		foreach ($data as $datum)
		{
			$datum->small_gravatar = get_gravatar($datum->gravatar, 16);
			if (empty($datum->url)) 
				$datum->url = "/meme/$datum->id";

			$mc = $datum->is_micro_content;
			if ($mc == 2) 
			{
				$datum->micro_content = media_filter($datum->url, 98, 80);
			}
			$result[] = $datum;
		}
		return $result;
	}


	private function count_memes($db, $where)
	{
		return $db->count('from posts p', $where);
	}
}
?>
