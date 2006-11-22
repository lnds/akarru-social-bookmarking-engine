<?php
include_once("akarru.lib/app_vars.php");

session_start();

mb_http_output("utf-8");
require('Smarty/Smarty.class.php');

class BM_Smarty extends Smarty {

	function BM_Smarty()
	{
		$this->Smarty();
		$this->template_dir = '';
		$this->compile_dir = '';
		$this->config_dir = '';
		$this->cache_dir = '';
		$this->plugins_dir[] = '';
		$this->caching = false;
		application_start();

	}

	function display($content)
	{
		parent::display($content);
		application_end();
	}
}

$smarty = new BM_Smarty;


include_once("config.php");
include_once('akarru.lib/database.php');
include_once('akarru.lib/users.php');
include_once('akarru.lib/categories.php');
include_once('akarru.lib/folksonomy.php');
include_once('akarru.lib/memes.php');
include_once('akarru.lib/form_manager.php');
include_once('akarru.lib/cache_manager.php');
include_once('locale');

$bm_db = new database();
$bm_users = new users($bm_db);
$bm_is_logged_in = 0;
$bm_is_logged_and_valid = 0;
$bm_is_banned = 0;
$bm_user = 0;
$bm_user_name="";
if ($bm_users->is_logged_in())
{
	$bm_user = $bm_users->user->ID;
	$bm_user_name = $bm_users->user->username;
	$bm_is_logged_in = 1;
	$bm_is_logged_and_valid = $bm_users->is_valid_account();
	$bm_is_banned = $bm_users->is_banned();
}

$bm_tags = new folksonomy($bm_db);

$smarty->assign('bf_date_posted', $bm_date_posted_format);
$smarty->assign('logged_in', $bm_is_logged_in);
$smarty->assign('logged_and_valid', $bm_is_logged_and_valid);
$smarty->assign('banned_account', $bm_is_banned);
$smarty->assign('logged_userid', $bm_user);
$smarty->assign('is_admin', $bm_users->get_is_admin());
$smarty->assign('logged_username', $bm_user_name);

$bm_page = 1;
if (isset($_GET['page']))
{
    $bm_page = intval($_GET['page']);
}

$smarty->assign('time', time());
$smarty->assign('page', $bm_page);

// This function is used to determine if ads should be displayed for a particular IP
// You can add your fixed IP to avoid clicking on google ads in the blogmemes network
// It would be a bummer to be ban for invalid clicks
function showGGAds()
{
// Example "222.222.222.22", "222.222.222.21"...
// Add your own IP and email the other members so they can do the same to their config
$noads = array();
$ip = $_SERVER['REMOTE_ADDR'];
  return (! in_array($ip, $noads));
}

// Error reporting functions
function mail_error($error) {
	global $bm_admin_email_address;
    mail($bm_admin_email_address, "Error on blogmemes", $error);
}

function logerror($error,$filename="errors",$send_email = 0)
{
    $date = date('l dS \of F Y h:i:s A');
    $ip = "[" . $_SERVER["REMOTE_ADDR"] . "] - resolved=[" . gethostbyaddr($_SERVER["REMOTE_ADDR"]) . "]";
    
    $report = $error;
    $report .= "\ndate=" . $date . "\nIP=" . $ip . " ";
    $report = $report . "\nlooked for : " . $_SERVER["REQUEST_URI"];
    $report = $report . "\nfrom : " . $_SERVER["HTTP_REFERER"];
    $report = $report . "\nuser-agent : " . $_SERVER["HTTP_USER_AGENT"];
    $report = $report . "\nquery-string : " . $_SERVER["QUERY_STRING"];
    $report = $report . "\nhost : " . $_SERVER["HTTP_HOST"];
    
    $logfile = $_SERVER["DOCUMENT_ROOT"] . "/cache/logs/" . $filename;
    $handleLog = fopen($logfile, "a");
    if ($handleLog)
    {
      fwrite($handleLog, $report . "\n");
      fclose($handleLog);  
    }
    
    if ($send_email)
    {
        mail_error($report);
    }
}

?>
