<?php
	if (empty($_GET['meme_id'])) {
		if (empty($meme_id)) {
			header("Location: index.php");
			exit();
			return;
		}
	}
  $meme_id = $_GET['meme_id'];
  include_once('akarru.lib/common.php');
  $smarty->assign('content_title', 'circulaci&oacute;n');


class Circulation {
	var $views;
	var $clicks;
	var $host;

	function Circulation($host, $views, $clicks)
	{
		$this->host   = $host;
		$this->clicks = $clicks;
		$this->views  = $views;
		$this->circulation = $clicks+$views;
	}

	function add($circ)
	{
		$this->clicks += $circ->clicks;
		$this->views  += $circ->views;
		$this->circulation = $this->clicks+$this->views;
	}
}

function start_element($parser, $name, $attr)
{
	global $views;
	global $clicks;
	if ($name == 'REFERRER') {
		$url = $attr['URL'];
		$url_data = @parse_url($url);
		if (empty($url_data['host'])) {
			$views['www.blogmemes.com'] += $attr['ITEMVIEWS'];
			$clicks['www.blogmemes.com'] += $attr['CLICKTHROUGHS'];
		}
		else
		{
			$host = $url_data['host'];
			$views[$host]  += $attr['ITEMVIEWS'];     
			$clicks[$host] += $attr['CLICKTHROUGHS']; 
		}
	}

}

function end_element()
{

}

$xml_parser = xml_parser_create();
xml_set_element_handler($xml_parser, "start_element", "end_element");

# CHANGE THIS!!!
$feed_uri = 'http://feeds.feedburner.com/PUT_YOUR_FEED_BURNER_URL';
$item_url = 'http://www.blogmemes.com/comment.php?meme_id='.$_GET['meme_id'];
$dates = date('Y-m-d', time()-86400*30).','.date('Y-m-d');
$url  = 'http://api.feedburner.com/awareness/1.0/GetResyndicationData?uri='.$feed_uri.'&itemurl='.$item_url.'&dates='.$dates;
$data = @file_get_contents($url);

xml_parse($xml_parser, $data);

xml_parser_free($xml_parser);

  $memes = new memes($bm_db, $bm_user);
  $meme = $memes->get_meme($meme_id);
  $smarty->assign('meme', $meme);

  $data = array();
  $total = new Circulation('TOTAL', 0, 0);
  foreach ($views as $key=>$value) {
	  $circulation = new Circulation($key, $value, $clicks[$key]); 
	  $data[] = $circulation;
	  $total->add($circulation);
  }
  $smarty->assign('views', $data);
  $smarty->assign('total', $total);
  $smarty->assign('clicks', $clicks);
  $smarty->assign('content', 'circulation');
  $smarty->display('master_page.tpl');
?>
