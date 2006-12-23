<?

class Category {

	public function __construct($cat_id, $cat_name='')
	{
		$cat_id = intval($cat_id);
		$db = Database::singleton();

		if ($cat_id > 0) 
		{
			$this->cat = $db->fetch_object('select * from post_cats where id = '.$cat_id);
		}
		else
		{
			$cat_name = $db->sanitize($cat_name);
			$this->cat = $db->fetch_object("select * from post_cats where cat_title = '$cat_name'");
		}
	}

	 /**
	 * 
	 * @return the value of the property for the category
	 */
	private function __get($name)
	{
		if (isset($this->cat->$name)) {
			return $this->cat->$name;
		}
		return '';
	}

	
}
?>
