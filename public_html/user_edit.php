<?php
  include_once('akarru.lib/common.php');
  if (!$bm_users->is_logged_in()) {
	  header("Location: /login.php");
	  exit();
	  return;
  }
  
  if (!$bm_users->get_is_admin())
  {
      header("Location: /403.php");
	  exit();
	  return;
  }
  
  include_once('common_elements.php');

  if (empty($_POST))
  {
      if (!isset($_GET['user_id']))
      {
          header("Location: /404.php");
          exit();
          return;
      }
  
      $user_id = (int) $_GET['user_id'];
      
      if ($user_id == 0)
      {
          header("Location: /404.php");
          exit();
          return;
      }
      
      $user = $bm_users->get_user_by_id($user_id);
  
      $smarty->assign('user_id', $user_id);
      $smarty->assign('email', $user->email);
      $smarty->assign('website', $user->website);
      $smarty->assign('blog', $user->blog);
      $smarty->assign('fullname', $user->fullname);
      $smarty->assign('banned', $user->disabled && $user->validated);
      $bm_title = $bl_profile_edit.' '.$user->username;
  }
  else
  {
      if (!isset($_POST['user_id']))
      {
          header("Location: /404.php");
          exit();
          return;
      }
  
      $user_id = (int) $_POST['user_id'];
           
      if ($user_id == 0)
      {
          header("Location: /404.php");
          exit();
          return;
      }
    
    $user = $bm_users->get_user_by_id($user_id);
    $bm_title = $bl_profile_edit.' '.$user->username;
    if (isset($_POST['ban_user']) ? $_POST['ban_user'] : false)
    {
        $bm_users->ban_user($user_id);
    }
    else
    {
        $bm_users->unban_user($user_id);
    }
           
	if (empty($_POST['email'])) {
		  $smarty->assign('error_email', 1);
	}
	else
	{
      if ($bm_users->edit_user($_POST))
      {
          $smarty->clear_cache(null,'db|users|profile|' . $user->username);
          header("Location: /user/" . $user->username);
          exit();
          return;
      }
    }
    
   $smarty->assign('user_id', $user_id);
   $smarty->assign('email', $user->email);
   $smarty->assign('website', $user->website);
   $smarty->assign('blog', $user->blog);
   $smarty->assign('fullname', $user->fullname);
   $smarty->assign('banned', $user->disabled && $user->validated);
   $bm_title = $bl_profile_edit.' '.$user->username;
 }

  $smarty->assign('content_title', $bm_title);
  $smarty->assign('content', 'user_edit');
  $smarty->display('master_page.tpl');
?>