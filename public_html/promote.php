<?php
  include_once('akarru.lib/common.php');
  include_once('common_elements.php');
  $smarty->assign('content_title', $content_title_promote);
  $smarty->assign('content', 'promote');
  $smarty->assign('show_ads', showGGAds());
  $smarty->display('master_page.tpl');
?>
