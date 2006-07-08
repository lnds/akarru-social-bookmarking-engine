<?php
include_once('lib/Akismet.class.php');

function is_spam($user_name, $user_email, $user_url, $user_comment, $permalink, $type="comment")
{
    global $bm_url;
    global $bm_akismet_api_key;
    
    if (empty($bm_akismet_api_key))
    {
        return false;
    }
    
    $akismet = new Akismet($bm_url, $bm_akismet_api_key);
    $akismet->setCommentAuthor($user_name);
    $akismet->setCommentAuthorEmail($user_email);
    $akismet->setCommentAuthorURL($user_url);
    $akismet->setCommentContent($user_comment);
    $akismet->setPermalink($permalink);
    $akismet->setCommentType($type);
    return $akismet->isCommentSpam();    
}

?>