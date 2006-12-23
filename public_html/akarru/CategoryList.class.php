<?php

class CategoryList 
{
	var $categories;

	public function __construct()
	{
		$db = Database::singleton();
		$this->load_categories($db);
	}

	private function load_categories($db)
	{
		$this->categories = $db->select_fields('ID, cat_title', 'from post_cats', '', 'order by cat_title');
	}
}
?>
