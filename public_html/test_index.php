<?
function microtime_float()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}

$time_start = microtime_float();
  include_once('akarru.lib/common.php');
$time_load_common = microtime_float();
  $memes = new memes($bm_db, $bm_user, $bm_promo_level);
  $data = $memes->get_memes($bm_page, '');
$time_data_processing = microtime_float();
  $smarty->assign('content_title', $bl_last_memes);
  $smarty->assign('memes', $data);
  $smarty->assign('page_title', 'blogmemes - '.$data[0]->title);
  if ($memes->pages > 50) 
	  $memes->pages = 50;
  if ($memes->pages > 1) 
	  $smarty->assign('pages', $memes->pages+1);
  $smarty->assign('$bm_message', $bl_promoted_message);
  $smarty->assign('content', 'memes_grid');
  $smarty->assign('content_feed_link', $bm_main_feeds);
  $smarty->assign('show_ads', true);
  echo $smarty->fetch('master_page.tpl');
$time_smarty = microtime_float();
echo $time_load_common - $time_start.',';
echo $time_data_processing - $time_load_common.',';
echo $time_smarty - $time_data_processing;
?>

