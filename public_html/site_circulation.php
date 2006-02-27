<?php
  include_once('akarru.lib/common.php');
  $smarty->assign('content_title', 'circulaci&oacute;n');



class Circulation {
	var $views;
	var $clicks;
	var $title;
	var $url;
	var $circulation;

	function Circulation($title, $url, $views, $clicks)
	{
		$this->title = $title;
		$this->url = $url;
		$this->views  = $views;
		$this->clicks = $clicks;
		$this->circulation = $clicks+$views;
	}

	function add($circ)
	{
		$this->clicks += $circ->clicks;
		$this->views  += $circ->views;
		$this->circulation = $this->clicks+$this->views;
	}
}

$circ_data = array();
  $total = new Circulation('TOTAL', 'http://www.blogmemes.com', 0, 0);


function start_element($parser, $name, $attr)
{
	global $circ_data;
	global $total;
	if ($name == 'ITEM') {
		$title = $attr['TITLE'];
		$url   = $attr['URL'];
		$circulation = new Circulation($title, $url, $attr['ITEMVIEWS'], $attr['CLICKTHROUGHS']); 
		$total->add($circulation);
		$circ_data[] = $circulation;
	}

}

function end_element()
{

}

$xml_parser = xml_parser_create();
xml_set_element_handler($xml_parser, "start_element", "end_element");

# Change THIS
$url  = 'http://api.feedburner.com/awareness/1.0/GetResyndicationData?uri=PUT_YOUR_FEED_BURNER_URL';
echo $url;
$data = @file_get_contents($url);

xml_parse($xml_parser, $data);

xml_parser_free($xml_parser);

function cmp_circ($a, $b)
{
	return $b->circulation - $a->circulation;
}

usort($circ_data, "cmp_circ");

  $smarty->assign('views', $circ_data);
  $smarty->assign('total', $total);
  $smarty->assign('content', 'site_circulation');
  $smarty->display('master_page.tpl');
?>

