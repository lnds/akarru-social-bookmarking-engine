<?php
include_once("akarru.lib/app_vars.php");

session_start();

// comment if your Blogmemes needs UTF-8
mb_http_output("ISO-8859-1");
// un comment if your Blogmemes needs UTF-8
//mb_http_output("utf-8");
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
include_once('locale.es');

$bm_db = new database();
$bm_users = new users($bm_db);
if ($bm_users->is_logged_in())
{
	$bm_user = $bm_users->user->ID;
	$bm_user_name = $bm_users->user->username;
	$bm_is_logged_in = 1;
}

$bm_tags = new folksonomy($bm_db);

$smarty->assign('community_sample', $bm_users->get_random_profile_links(9));
$smarty->assign('tags', $bm_tags->fetch_top(21));
$smarty->assign('bf_date_posted', 'publicado el %d/%m/%Y a las %R');
$smarty->assign('logged_in', $bm_is_logged_in);
$smarty->assign('logged_userid', $bm_user);
$smarty->assign('logged_username', $bm_user_name);

$bm_page = intval($_GET['page']);
if (empty($bm_page)) 
	$bm_page = 1;

$smarty->assign('time', time());
$smarty->assign('page', $bm_page);

if (isset($_APP['cats'])) {
  $cats = $_APP['cats'];
}
else
{
	$bm_cats = new categories($bm_db);
	$bm_cats = $bm_cats->fetch_all();
	$smarty->assign('bm_cats', $bm_cats);
	foreach ($bm_cats as $cat) {
		$cats[] = '<a href="show_cat.php?cat_id='.$cat->ID.'" >'.$cat->cat_title.'</a>';
	}
	$_APP['cats'] = $cats;
}
$smarty->assign('cats_array', $cats);

?>
