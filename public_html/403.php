<?php
header("HTTP/1.1 403 Forbidden");
header("Status: 403 Forbidden");
    
include_once('akarru.lib/common.php');
logerror("403 Forbidden","403errors");

include_once('common_elements.php');

$smarty->assign('content_title', "403 Access denied !");
  
$smarty->assign('content', '403');
$smarty->assign('content_feed_link', $bm_main_feeds);
$smarty->assign('show_ads', false);
$smarty->display('master_page.tpl');
?>
