<?php
/**
 * @package AkarruCPE
 * @version 0.6
 * @copyright (c) 2006 Eduardo Diaz Cortes
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License 
 * @author Eduardo Diaz <ediaz@lnds.net>
 */

include_once('akarru/common.php');
$id = request_value('meme_id', 0);
$meme = new Meme($id);
$template = new MemeTemplate('master');
if (is_post_back() && $template->user->is_logged_in())
{
	$meme->add_comment(request_value('comment'), request_value('user_id'));
}
$template->set_data($meme);
$template->add_tab('Meme');
$template->display();

?>
