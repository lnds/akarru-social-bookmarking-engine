<?php
header("HTTP/1.1 404 Not Found");
header("Status: 404 Not Found");
    
include_once('akarru.lib/common.php');
logerror("404 not found","404errors");

include_once('common_elements.php');

$smarty->assign('content_title', "404 Not found !");
  
$smarty->assign('content', '404');
$smarty->assign('content_feed_link', $bm_main_feeds);
$smarty->assign('show_ads', false);
$smarty->display('master_page.tpl');
?>
