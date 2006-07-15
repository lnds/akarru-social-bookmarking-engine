<?php
  include_once('akarru.lib/common.php');
  include_once('common_elements.php');
  if (!$bm_users->is_logged_in())
  {
      $url = "/login.php";
      header("Location: $url");
      exit();
      return;
  }
  else if ($bm_users->is_valid_account())
  {
      $url = "/validated_user.php";
      header("Location: $url");
      exit();
      return;
  }
  else if ($bm_users->is_banned())
  {
      $url = "/403.php";
      header("Location: $url");
      exit();
      return;
  }
  
  
  if(!empty($_POST))
  {
    $user_name = $bm_users->get_user_name();
    $email = $bm_users->get_user_email();
    
    if (!$bm_users->sendValidationLink($user_name, $email))
    {
       $smarty->assign('error_cant_send_validation_link', true);
	   $bm_errors++;
    }
    else
    {
      $url = "/validate_user.php";
      header("Location: $url");
      exit();
      return;
    }
  }

  $email = $bm_users->get_user_email();
  $smarty->assign('email', $email);
  $smarty->assign('content', 'send_validation');
  $smarty->assign('content_title', $bl_send_validation);
  $smarty->assign('community', true);
  $smarty->display('master_page.tpl');
?>