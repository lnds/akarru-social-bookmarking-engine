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
$tag = new Tag(0, request_value('tag_name',''));
print $tag->ID . "  ".$tag->tag;
$memes = new MemeList($page, 'where p.ID in (select post_id from tags_posts where tag_id = '.$tag->ID.')', 0, 'order by date_posted desc');
$template = new GridTemplate('master');
$template->set_data($memes);
$template->add_tab($tag->tag);
$template->display();
?>
