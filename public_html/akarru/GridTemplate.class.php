<?php

class GridTemplate extends Template
{
	var $page_size = 35;

	function set_data($data)
	{
		$this->data = $data;
		$this->assign('memes', $data->get_memes());
		$this->assign('pagination', $this->paginate());
	}

	function paginate()
	{
		$current_page = $this->data->page;
		$max_pages = $this->data->pages;
		$pages = array();
		$pos = floor($current_page / $this->page_size);
		$left = ($this->page_size*$pos+1);
		$right = min($left+$this->page_size, $max_pages);
		if ($left > $this->page_size) {
			$pages[] =  '<a href="?page='.($left-$this->page_size).'">&lt;&lt;</a>'; 
		}
		for ($i = $left; $i < $right; $i++) 
		{
			if ($i == $current_page) 
			{
				$pages[] = '<b>'.$i.'</b>';
			}
			else
			{
				$pages[] = '<a href="?page='.$i.'">'.$i.'</a>';
			}
		}
		if ($right < $max_pages) 
		{
			$pages[] = '<a href="?page='.$right.'">&gt;&gt;</a>';
		}
		return $pages;
	}
}
?>
