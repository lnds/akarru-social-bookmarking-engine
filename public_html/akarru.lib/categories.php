<?php
class categories {

	function categories($db)
	{
		$this->db = $db;
	}


	function fetch_all()
	{
		static $all;

		if (empty($all)) {
			$sql="select pc.cat_title,pc.ID,count(p.ID) as post_count from post_cats pc left join posts p on pc.ID=p.category group by pc.cat_title,pc.cat_desc,pc.ID order by pc.cat_title asc limit 50";
			$all = $this->db->fetch($sql);
		}
		return $all;
	}

	function get_category_name($cat_id)
	{
		$sql="select pc.cat_title as category_name from post_cats pc where pc.ID = $cat_id";
		return $this->db->fetch_scalar($sql);
	}
}
?>
