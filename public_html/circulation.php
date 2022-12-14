<?php
	$meme_id = intval($_GET['meme_id']);
	if ($meme_id == 0) {
        logerror("circulation.php: meme_id = 0", "phpErrors");
        header('Location: /404.php');
        exit();
		return;
	}
  include_once('akarru.lib/common.php');
  include_once('common_elements.php');
  $smarty->assign('content_title', $content_title_circulation);


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
	global $bm_web_address;
	if ($name == 'REFERRER') {
		$url = $attr['URL'];
		$url_data = @parse_url($url);
		if (empty($url_data['host'])) {
			$views[$bm_web_address] += $attr['ITEMVIEWS'];
			$clicks[$bm_web_address] += $attr['CLICKTHROUGHS'];
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

$feed_uri = $bm_url_feeds;
$item_url = $bm_url . 'meme/' . $meme_id;
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
  $smarty->assign('show_ads', showGGAds());
  $smarty->display('master_page.tpl');
?>
