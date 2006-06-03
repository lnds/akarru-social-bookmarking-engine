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
			$sql="select pc.cat_title,pc.ID,pc.disabled,count(p.ID) as post_count from post_cats pc left join posts p on pc.ID=p.category group by pc.cat_title,pc.cat_desc,pc.ID,pc.disabled order by pc.cat_title asc limit 50";
			$all = $this->db->fetch($sql);
		}
		return $all;
	}
    
    function fetch_all_enabled()
	{
		static $all;

		if (empty($all)) {
			$sql="select pc.cat_title,pc.ID,pc.disabled,count(p.ID) as post_count from post_cats pc left join posts p on pc.ID=p.category WHERE pc.disabled=FALSE group by pc.cat_title,pc.cat_desc,pc.ID,pc.disabled order by pc.cat_title asc limit 50";
			$all = $this->db->fetch($sql);
		}
		return $all;
	}

	function get_category_name($cat_id)
	{
		$cat_id = sanitize($cat_id);
		$sql="select pc.cat_title as category_name from post_cats pc where pc.ID = $cat_id";
		return $this->db->fetch_scalar($sql);
	}
    
    function get_category_description($cat_id)
    {
		$cat_id = sanitize($cat_id);
		$sql="select pc.cat_desc as category_description from post_cats pc where pc.ID = $cat_id";
		return $this->db->fetch_scalar($sql);
	}
    
    function delete_category($cat_id)
    {
        if ($this->check_category_id_exists($cat_id)) 
        {
            $cat_id = sanitize($cat_id);
		    $sql="delete from post_cats where ID = $cat_id";
            return $this->db->execute($sql);
        }
        else
        {
            return FALSE;
        }
    }
    
    function disable_category($cat_id)
    {
        if ($this->is_category_enabled($cat_id)) 
        {
            $cat_id = sanitize($cat_id);
		    $sql="update post_cats SET disabled = TRUE WHERE ID = $cat_id";
            return $this->db->execute($sql);
        }
        else
        {
            return FALSE;
        }
    }
    
    function enable_category($cat_id)
    {
        if ($this->is_category_enabled($cat_id)) 
            return FALSE;
            
        $cat_id = sanitize($cat_id);
        $sql="update post_cats SET disabled = FALSE WHERE ID = $cat_id";
        return $this->db->execute($sql);
    }
    
    function add_category($cat_name, $cat_description="")
    {
        if ($this->check_category_exists($cat_name)) 
			return FALSE;
        
        $cat_name = sanitize($cat_name);
        $cat_description = sanitize($cat_description);
		$sql="insert into post_cats (cat_title, cat_desc) values ($cat_name, $cat_description)";
        return $this->db->execute($sql);
    }
    
    function update_category($cat_id, $cat_name, $cat_description="")
    {
        if ($this->check_category_id_exists($cat_id)) 
		{
            $cat_id = sanitize($cat_id);
            $cat_name = sanitize($cat_name);
            $cat_description = sanitize($cat_description);
            $sql="UPDATE post_cats SET cat_title = $cat_name, cat_desc = $cat_description WHERE ID = $cat_id";
            return $this->db->execute($sql);
        }
        else
        {
        	return FALSE;
        }
    }
    
    function check_category_exists($cat_name, $exclude_cat_id="")
	{
        $cat_name = sanitize($cat_name);
        if (empty($exclude_cat_id))
        {
            return $this->db->fetch_scalar("select 1 from post_cats where cat_title = $cat_name");
        }
        else
        {
            $exclude_cat_id = sanitize($exclude_cat_id);
            return $this->db->fetch_scalar("select 1 from post_cats where cat_title = $cat_name AND ID <> $exclude_cat_id");
        }
	}
    
    function check_category_id_exists($cat_id)
	{
        $cat_id = sanitize($cat_id);
		return $this->db->fetch_scalar("select count(*) from post_cats where ID = '$cat_id'");
	}
    
    function is_category_enabled($cat_id)
    {
        $cat_id = sanitize($cat_id);
		return $this->db->fetch_scalar("select count(*) from post_cats where ID = '$cat_id' AND disabled = FALSE");
    }
	
}
?>
