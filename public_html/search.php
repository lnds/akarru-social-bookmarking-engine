<?
  include_once('akarru.lib/common.php');
  $smarty->assign('content_title', 'busqueda');
  $memes = new memes($bm_db, $bm_user, $bm_promo_level);
  if (empty($_POST)) {
	  $smarty->assign('memes', $memes->get_memes($page));
  }
  else
  {
	  $smarty->assign('memes', $memes->search_memes($search));
  }
  if ($memes->pages > 1) 
	  $smarty->assign('pages', $memes->pages+1);
  $smarty->assign('bm_message', 'criterio de b&uacute;squeda: '.$search);
  $smarty->assign('content', 'memes_grid');
  $smarty->display('master_page.tpl');
?>
