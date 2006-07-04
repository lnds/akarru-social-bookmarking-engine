<?php

class folksonomy {

	function folksonomy($db)
	{
		$this->db = $db;
	}


	function count()
	{
		$sql = 'select count(distinct tag) from tags';
		return $this->db->fetch_scalar($sql);
	}

	function count_pages($page_size=250)
	{
		return ceil($this->count()/$page_size);
	}
	function fetch_all($page, $page_size=250)
	{
		// Kenji: this version is better at setting the size of the tags
        $sql = "select t.tag, tp.tag_id, count(tp.post_id) memes from tags_posts tp join tags t on t.id = tp.tag_id group by t.tag, tp.tag_id";
		$tags = $this->db->fetch($sql);
		$nbTimes = array();
        
        foreach ($tags as $tag)
		{
           $nbTimes[] = $tag->memes;
        }
        $maxNbTimes = max($nbTimes);
        $minNbTimes = min($nbTimes);
        $nbFontSize = 8;
        $threshold = ($maxNbTimes - $minNbTimes)/$nbFontSize;
        
		$p = ($page-1) * $page_size;
		$sql = "select t.tag, tp.tag_id, count(tp.post_id) memes from tags_posts tp join tags t on t.id = tp.tag_id group by t.tag, tp.tag_id limit $p, $page_size";
		$tags = $this->db->fetch($sql);
        
		foreach ($tags as $tag)
		{
            $tag->font_size = 9 + ceil($tag->memes - $min / $threshold) * 2;
			$result[] = $tag;
		}
		return $result;
	}


	function fetch_top($limit)
	{
		$sql = 'select t.tag, tp.tag_id, count(tp.post_id) memes from tags t join tags_posts tp on  tp.tag_id = t.id group by t.tag, tp.tag_id order  by memes desc limit '.$limit; 
		return $this->db->fetch($sql);
	}

	function fetch_random($limit)
	{
		$sql = 'select t.tag, tp.tag_id, count(tp.post_id) memes from tags_posts tp join tags t on t.id = tp.tag_id group by t.tag, tp.tag_id ';
		$sql .= ' order by rand() limit '.$limit;
		return $this->db->fetch($sql);
	}


	function fetch_by_meme($meme_id)
	{
		$sql = "select t.ID, t.tag from tags t join tags_posts tp on t.id = tp.tag_id where tp.post_id = $meme_id";
		return $this->db->fetch($sql);
	}
}
?>
