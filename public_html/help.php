<?php
  $page = $_GET['page'];
  include_once('akarru.lib/common.php');
  $smarty->assign('content_title', $bl_help);
  $smarty->assign('content', 'help');
  $smarty->assign('community', true);
  $smarty->display('master_page.tpl');
?>

