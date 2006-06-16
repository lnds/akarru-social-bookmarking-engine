<?php
  include_once('akarru.lib/common.php');
  $smarty->assign('content_title', 'folksonom&iacute;a');
  $smarty->assign('content', 'folksonomy_grid');
  $smarty->assign('all_tags', $bm_tags->fetch_all($bm_page));
  $smarty->assign('pages', $bm_tags->count_pages()+1);
  $smarty->display('master_page.tpl');
?>

