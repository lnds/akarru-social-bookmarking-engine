<?php
  include_once('akarru.lib/common.php');
  
  include_once('common_elements.php');
  
  $smarty->assign('content_title', $bl_about);
  $smarty->assign('content', 'about');
  $smarty->assign('show_ads', showGGAds());
  $smarty->display('master_page.tpl');
?>
