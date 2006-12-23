<?php
/**
 * @package AkarruCPE
 * @subpackage index
 * @version 0.6
 * @copyright (c) 2006 Eduardo Diaz Cortes
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License 
 * @author Eduardo Diaz <ediaz@lnds.net>
 */

include_once('akarru/common.php');
$page = request_value('page', 1);
if (empty($page)) {
	$page = 1;
}
$memes = new MemeList($page, 'where promoted = false', 0, 'order by date_posted desc');
$template = new GridTemplate('master');
$template->set_data($memes);
$template->set_selector(1);
$template->display();
?>

