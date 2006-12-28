<?
function microtime_float()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}

$time_start = microtime_float();
require('akarru/common.php');
$time_load_common = microtime_float();
$page = request_value('page', 1);
$memes = new MemeList($page);
$template = new GridTemplate('master');
$template->set_data($memes);
$template->set_selector(0);
$time_data_processing = microtime_float();
$template->display();
$time_smarty = microtime_float();
echo $time_load_common - $time_start.',';
echo $time_data_processing - $time_load_common.',';
echo $time_smarty - $time_data_processing;
?>

