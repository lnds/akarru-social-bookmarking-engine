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
$search = request_value('search','');
$memes = new MemeList($page, 'where title like \'%'.$search.'%\' or content like \'%'.$search.'%\' ', 0, 'order by date_posted desc');
$template = new GridTemplate('master');
$template->set_data($memes);
$template->set_selector(0);
$template->display();
?>

