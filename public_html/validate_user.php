<?php
  include_once('akarru.lib/common.php');
  include_once('common_elements.php');
  
     if(!empty($_GET))
     {
        $key = $_GET['k'];
        $hash = $_GET['h'];
        $bm_errors = 0;
        if (empty($key) || ! preg_match('/^[a-z0-9]+$/i', $key))
        {
          $smarty->assign('error_code', true);
          $smarty->assign('key', $key);
          $bm_errors++;
        }
        else
        {
          $smarty->assign('key', $key);
        }
        
        if (empty($hash) || ! preg_match('/^[a-z0-9]+$/i', $hash))
        {
          $smarty->assign('error_code', true);
          $smarty->assign('hash', $hash);
          $bm_errors++;
        }
        else
        {
          $smarty->assign('hash', $hash);
        }
        
        if ($bm_errors == 0)
        {
          if (!$bm_users->verifyValidationLink($hash, $key))
          {
              $url = "/send_validation.php";
              header("Location: $url");
		      exit();
              return;
          }
          else
          {
              $url = "/validated_user.php";
              header("Location: $url");
              exit();
              return;
          }
        }
        else
        {
              $url = "/send_validation.php";
              header("Location: $url");
		      exit();
              return;
        }
        
     }
  
    if (!empty($_POST))  {
      $key = $_POST['k'];
      $bm_errors = 0;

      if (empty($key) || ! preg_match('/^[a-z0-9]+$/i', $key)) {
          $smarty->assign('error_code', true);
          $smarty->assign('key', $key);
          $bm_errors++;
      }
      else{
          $smarty->assign('key', $key);
      }
      
      if ($bm_errors == 0) {
          if (!$bm_users->verifyValidationKey($key))
          {
              $url = "/send_validation.php";
              header("Location: $url");
		      exit();
              return;
          }
          else
          {
              $url = "/validated_user.php";
              header("Location: $url");
              exit();
              return;
          }
      }
  }
  
  $smarty->assign('content_title', $bl_validate_user);
  $smarty->assign('content', 'validate_user');
  $smarty->assign('show_ads', showGGAds());
  $smarty->display('master_page.tpl');
?>
