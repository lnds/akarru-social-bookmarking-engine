<?php
  include_once('akarru.lib/common.php');
  include_once('common_elements.php');
      
  $smarty->assign('content', 'validated_user');
  $smarty->assign('content_title', $bl_validated_user);
  $smarty->assign('community', true);
  $smarty->assign('page', $page);
  $smarty->display('master_page.tpl');
?>