<?php
/**
 * @package AkarruCPE
 * @version 0.6
 * @copyright (c) 2006 Eduardo Diaz Cortes
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License 
 * 
 * @author Eduardo Diaz <ediaz@lnds.net>
 * @since Version 0.6
 */
 
/**
 * Used for voting, replace old ajax_vote.php.
 * This interface uses json for serialization of the vote.
 */
header("Content-Type: text/plain");
include_once('akarru/common.php');
$meme_id = request_value('meme_id', 0);
$user_id = request_value('user_id', 0);
if ($meme_id == 0) 
	echo json_encode(array('status'=>'error'));
else
{
	$meme = new Meme($meme_id);
	$vote = $meme->add_vote($user_id);
	echo json_encode($vote);
}
?>
