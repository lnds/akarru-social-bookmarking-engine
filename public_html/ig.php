<?php
// Todo use a cache ?
// => a cache file for each combination of parameters !?
// or separate cache file for the queue_memes and main page memes ?
// Todo test the username when (and if) it is used
include_once('akarru.lib/common.php');

$gadgetsCache = $_SERVER["DOCUMENT_ROOT"] . "/cache/gadgets/";
$cache_tolerance = 60*60*6; // in seconds

function printMemes($number, $queue = false)
{
    global $cache_tolerance;
    global $gadgetsCache;
    global $memes;
    global $bm_url;
    
    $googleGadgetCacheFile = $gadgetsCache . "googleGadgetMemes" . $number;
    if ($queue)
    {
        $googleGadgetCacheFile .= "Queue";
    }

    flush();
    // check how old the cache file is
    if (file_exists($googleGadgetCacheFile))
    {
        clearstatcache();  // filemtime info gets cached so we must ensure that the cache is empty
        $time_difference = time() - filemtime($googleGadgetCacheFile);
    }
    else
    {
        $time_difference = $cache_tolerance;  // force update
    }
    
    if  ($time_difference >= $cache_tolerance)
    {	// update the cache if needed
      ob_start();
      
      // Queue or Main page memes
      if ($queue)
      {
        $data = $memes->get_new_memes();
      }
      else
      {
        $data = $memes->get_memes();
      }
      
      $data = array_slice($data, 0, $number);
        
      foreach($data as $meme)
      {
        $meme_url = $memes->get_permalink($meme->ID);
        $vote_url = $bm_url . "vote.php?meme_id=" . $meme->ID;
        if ($queue)
        {
        ?>
        <div class="bm_q_meme">
            <a href="<? echo($vote_url ); ?>" title="<?php echo($bl_votes); ?>" class="bm_q_link"><? echo($meme->votes); ?><br /></a><a href="<? echo($meme_url ); ?>"><? echo($meme->title); ?></a>
        </div>
        <?php

        }
        else
        {
        ?>
        <div class="bm_meme">
            <a href="<? echo($vote_url ); ?>" title="<? echo($bl_vote); ?>" class="bm_link"><? echo($meme->votes); ?><br /></a><a href="<? echo($meme_url ); ?>"><? echo($meme->title); ?></a>
        </div>
        <?php
        }
      }

      $output = ob_get_contents();
      ob_end_clean();
       
      $handle = fopen($googleGadgetCacheFile, "w");
        
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
        @readfile($googleGadgetCacheFile);
    }
}


  $number = $_GET['num'];
  $queue_number = $_GET['qnum'];
  
  // We need numbers
  if (!is_numeric($number) || !is_numeric($queue_number))
  {
  ?>
Invalid parameters.
  <?
  exit();
  }
  
  $memes = new memes($bm_db, $bm_user, $bm_promo_level);  
  // At least one and no more than 5
  if ($number <= 0)
  {
    $number = 1;
  }
  else if($number > 5)
  {
    $number = 5;
  }
  
  printMemes($number);
  
  if ($queue_number > 0)
  {
      // no more than 5
      if($queue_number > 5)
      {
        $queue_number = 5;
      }
      
      printMemes($queue_number, true);
  }
?>