<?php
  $page = intval($_GET['page']);
  include_once('akarru.lib/common.php');
  include_once('common_elements.php');
  $smarty->assign('content_title', $bl_help);
  $smarty->assign('content', 'help');
  $smarty->assign('community', true);
  $smarty->assign('show_ads', showGGAds());
  $smarty->display('master_page.tpl');  
?>

