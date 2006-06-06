<?php
include_once('akarru.lib/common.php');
$feedFlareCache = $_SERVER["DOCUMENT_ROOT"] . "/cache/feedflares/";
$cache_tolerance = 60*60; // in seconds

function printFeedFlare($meme_id)
{
global $cache_tolerance;
global $feedFlareCache;
global $bm_db;
global $bm_user;
global $bm_promo_level;
global $bm_url;

$feedFlareCacheFile = $feedFlareCache . $meme_id;

    flush();
    // check how old the cache file is
    if (file_exists($feedFlareCacheFile))
    {
        clearstatcache();  // filemtime info gets cached so we must ensure that the cache is empty
        $time_difference = time() - filemtime($feedFlareCacheFile);
    } else
    {
        $time_difference = $cache_tolerance;  // force update
    }
    
    if  ($time_difference >= $cache_tolerance)
    {	// update the cache if need be
        ob_start();
    
        $bm_memes = new memes($bm_db, $bm_user, $bm_promo_level);
        $nbOfVotes = $bm_memes->get_votes($meme_id);
        $voteURL = $bm_url . "vote.php?meme_id=" . $meme_id;
        ?>
<FeedFlare>
<Text>Omoshirokatta? Touhyou wo! <?php echo $nbOfVotes; ?> hyou.</Text>
<Link href="<?php echo $voteURL; ?>"/>
</FeedFlare>
        <?php       
        
        $output = ob_get_contents();
        ob_end_clean();
        
        $handle = fopen($feedFlareCacheFile, "w");
        
        if ($handle)
        {
           fwrite($handle, $output);
           fclose($handle);
            print $output;   
        }
        else
        {
            print $output;
        }
    }
    else
    {
        @readfile($feedFlareCacheFile);
    }
}

if(isset($_GET['url']))
{
     $url_format = $bm_url  . "comment.php?meme_id=";
     $startPos = strpos($_GET['url'], $url_format);
     if ($startPos === false)
     {
     ?>
<FeedFlare>
<Text>Vote link: wrong URL format!</Text>
<Link href="<?php echo $bm_url; ?>"/>
</FeedFlare>
<?php
        exit();
        return;
     }
     
     $startPos = $startPos + strlen($url_format);
     $meme_id = substr($_GET['url'], $startPos);
    if (is_numeric($meme_id))
    {
        printFeedFlare($meme_id);
        exit();
        return;
    }
    else
    {
        ?>
        <FeedFlare>
        <Text>Vote link: wrong meme id!</Text>
        <Link href="<?php echo $bm_url; ?>"/>
        </FeedFlare>
        <?php
        exit();
        return;
    }
}
else
{
?>
<FeedFlare>
<Text>Vote link: no URL!</Text>
<Link href="<?php echo $bm_url; ?>"/>
</FeedFlare>
<?php
}
?>