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

// Functions below are not yet implemented
function is_banned_ip($ip)
{
    return false;
}

function is_banned_url($url)
{
    return false;
}

function is_banned_email_service($email_address)
{
// pookmail.com, mailinator.com,SneakEmail.com,SpamGourmet.com, spambob.com .net .org
// dodgeit
// spamhole
// jetable
// mytrashmail
// mailnull.com
// mailexpire.com
// mailshell.com
// mailzilla.com
// tempinbox.com
// e4ward.com, spamex.com, and mailias.com.
// maileater.com
// g@mailinator.comh 75000
//g@mytrashmail.comh 45000
//g@dodgeit.comh 30000
//g@jetable.comh 26000
//g@spamhole.comh 5000
//g@spambobh 5000
//g@tempinbox.comh 200
// also in check for email or register we should remove the + and parts after it : joe+spammers@gmail.com => joe@gmail.com to avoid multiple registrations
    return false;
}
?>