<?
  include_once('akarru.lib/common.php');
  if ($bm_user != 1) {
	  header("Location: index.php");
	  exit();
	  return;
  }
  
  $cat_id = (int)$_GET['cat_id'];
  if (empty($cat_id)) {
	  $cat_id = (int)$_POST['cat_id'];
  }
  
  if (!empty($cat_id) && $cat_id <= 0) {
      header("Location: admin_categories.php");
	  exit();
	  return;
  }
  
  $categories = new categories($bm_db);
  $bm_cats = $categories->fetch_all();
  $smarty->assign('bm_cats', $bm_cats);
    
  if (empty($_POST))
  {
      if (empty($cat_id) || $cat_id)
      {
        // Add a category
        $smarty->assign('cat_name', "");
        $smarty->assign('cat_description', "");
        $smarty->assign('content_title', 'Add a category');
        $smarty->assign('content', 'add_category');
      }
      else
      {
        // Edit an existing category
        $smarty->assign('cat_id', $cat_id);
        $smarty->assign('cat_name', $categories->get_category_name($cat_id));
        $smarty->assign('cat_description', $categories->get_category_description($cat_id));
        $smarty->assign('content_title', 'Modify a category');
        $smarty->assign('content', 'edit_category');
      }
  }
  else
  {
    if (!empty($_POST['cat_name']))
    {
      $cat_name = $_POST['cat_name'];
      $cat_description = $_POST['cat_description'];
      
      if ($categories->check_category_exists($cat_name, $cat_id))
      {
        $smarty->assign('error_category_name_exists', true);
      }
      else
      {
          $result = false;
          if (empty($cat_id))
          {
            // Add a category
            $result = $categories->add_category($cat_name, $cat_description);
          }
          else
          {
            $result = $categories->update_category($cat_id, $cat_name, $cat_description);
          }
          
          if ($result)
          {
            header("Location: admin_categories.php");
            exit();
            return;
          }
          else
          {
            $smarty->assign('error_id', true);
          }
      }
    }
  }

  $smarty->assign('content_feed_link', $bm_main_feeds);
  $smarty->display('master_page.tpl');
?>
