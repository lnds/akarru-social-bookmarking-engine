<?php
include_once('akarru.lib/common.php');


if(isset($_GET['meme_id']))
{
     $meme_id = (int) $_GET['meme_id'];
     if ($meme_id != 0)
     {
        $voted = 0;
        $bm_memes = new memes($bm_db, $bm_user, $bm_promo_level);
        if ($bm_users->is_logged_in() && $bm_users->is_valid_account())
        {
            $user_id = $bm_users->get_user_id();
            if ($user_id == 1)
            {
               $bm_memes->promote($meme_id);
               $voted = 1;
            }
            else if(!$bm_memes->check_votes_user($meme_id,$user_id))
            {
                $bm_memes->vote($meme_id,$user_id);
                $voted = 1;
            }
        }
        else
        {
            if ($bm_memes->vote_anon($meme_id))
            {
                $voted = 1;
            }
        }
        
        if ($voted == 1)
        {
            // We need to update the cache for the gadgets
            processCache($meme_id);
        }
                
        header("Location: /meme/".$meme_id ."&voted=" .$voted );
        exit();
        return;
    }
    else
    {
        header("Location: /index.php");
        exit();
        return;
    }
}

header("Location: /index.php");
exit();
return;

?>
