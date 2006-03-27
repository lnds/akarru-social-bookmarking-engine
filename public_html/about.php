<?php
  include_once('akarru.lib/common.php');
  $smarty->assign('content_title', $bl_about);
  $smarty->assign('content', 'about');
  $smarty->display('master_page.tpl');
?>
