<?php
function microtime_float()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}

$start_time = microtime_float();

  include_once('akarru.lib/common.php');
  $smarty->assign('content_title', $bl_about);
  $smarty->assign('content', 'about');
  $smarty->display('master');
$end_time = microtime_float();
print($end_time - $start_time)  ;
?>
