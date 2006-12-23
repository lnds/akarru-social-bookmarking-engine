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
$cat = new Category(0, request_value('cat_name',''));
$memes = new MemeList($page, 'where category = '.$cat->ID, 0, 'order by date_posted desc');
$template = new GridTemplate('master');
$template->set_data($memes);
$template->add_tab($cat->cat_title);
$template->display();
?>
