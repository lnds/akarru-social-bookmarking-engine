<?
  include_once('akarru.lib/common.php');
  if ($bm_user != 1) {
      logerror("admin.php: bm_user not admin", "phpErrors");
	  header("Location: /403.php");
	  exit();
	  return;
  }
  
  include_once('common_elements.php');
  
  $categories = new categories($bm_db);
  $bm_cats = $categories->fetch_all_enabled();
  $smarty->assign('bm_cats', $bm_cats);
  
  if (!empty($_GET))
  {
	  if (!empty($_GET['delete'])) {
		  // try to delete the category
          // TODO
          $cat_id = (int) $_GET['delete'];
          if ($cat_id > 0)
          {
            if ($categories->delete_category($cat_id))
            {
                $smarty->clear_cache(null,'db|categories');
                header("Location: admin_categories.php");
                exit();
			    return;
            }
            else
            {
               $smarty->assign('error_id', true);
            }
          }
          else
          {
            $smarty->assign('error_id', true);
          }
	  }
	  else if (!empty($_GET['edit']) && !empty($_GET['name']))
      {
		  // try to edit the category
          // TODO
          $cat_id = (int)$_GET['edit'];
          if ($cat_id > 0)
          {
              $cat_name = $_GET['name'];
              if ($categories->edit_category($cat_id, $cat_name))
              {
                $smarty->clear_cache(null,'db|categories');
                header("Location: admin_categories.php");
                exit();
                return;
              }
              else
              {
                $smarty->assign('error_id', true);
              }
          }
          else
          {
            $smarty->assign('error_id', true);
          }
      }
      else if (!empty($_GET['enable'])) {
		  // try to enable the category
          
          $cat_id = (int) $_GET['enable'];
          if ($cat_id > 0)
          {
            if ($categories->enable_category($cat_id))
            {
                $smarty->clear_cache(null,'db|categories');
                header("Location: admin_categories.php");
                exit();
			    return;
            }
            else
            {
               $smarty->assign('error_id', true);
            }
          }
          else
          {
            $smarty->assign('error_id', true);
          }
	  }
      else if (!empty($_GET['disable'])) {
		  // try to disable the category
          
          $cat_id = (int) $_GET['disable'];
          if ($cat_id > 0)
          {
            if ($categories->disable_category($cat_id))
            {
                $smarty->clear_cache(null,'db|categories');
                header("Location: admin_categories.php");
                exit();
			    return;
            }
            else
            {
               $smarty->assign('error_id', true);
            }
          }
          else
          {
            $smarty->assign('error_id', true);
          }
	  }
  }
  
  $smarty->assign('content_title', 'Administration of the categories');
  $smarty->assign('content', 'admin_categories');
  $smarty->assign('content_feed_link', $bm_main_feeds);
  $smarty->display('master_page.tpl');
?>
