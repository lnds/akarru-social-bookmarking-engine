<?
  include_once('akarru.lib/common.php');
  $smarty->assign('content_title', $bl_search);
  $smarty->assign('page_title', 'blogmemes - '.$bl_search);

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
  $search = sanitize($search);
  if ($memes->pages > 1) 
	  $smarty->assign('pages', $memes->pages+1);
  $smarty->assign('bm_message', 'criterio de b&uacute;squeda: '.$search);
  $smarty->assign('content', 'memes_grid');
  $smarty->display('master_page.tpl');
?>
