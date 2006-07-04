<?
  include_once('akarru.lib/common.php');
  include_once('common_elements.php');
  $smarty->assign('content_title', $bl_search);
  $smarty->assign('sub_title', $bl_search);
  $memes = new memes($bm_db, $bm_user, $bm_promo_level);
  if (empty($_POST)) {
	  $search = $_GET['search'];
	  $smarty->assign('memes', $memes->get_memes($bm_page));
  }
  else
  {
	  $search = $_POST['search'];
	  $smarty->assign('memes', $memes->search_memes($search));
  }
  if ($memes->pages > 1) 
	  $smarty->assign('pages', $memes->pages+1);
  $smarty->assign('bm_message', $content_title_search.$search);
  $smarty->assign('content', 'memes_grid');
  $smarty->display('master_page.tpl');
?>
