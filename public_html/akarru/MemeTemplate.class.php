<?php

class MemeTemplate extends Template
{
	var $data;

	public function set_data($data)
	{
		$this->data = $data->load();
		$this->assign('meme', $this->data);
		$this->tags = $data->tags;
		$this->assign('meme_tags', $this->tags);
		$this->comments = $data->comments;
		$this->assign('comments', $this->comments);
		$this->assign('content', 'meme');
	}
}

?>
