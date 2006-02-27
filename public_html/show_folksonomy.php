<?php
  include_once('akarru.lib/common.php');
  $smarty->assign('content_title', 'folksonom&iacute;a');
  $smarty->assign('content', 'folksonomy_grid');
  $smarty->assign('all_tags', $bm_tags->fetch_all(100));
  $smarty->display('master_page.tpl');
?>

