<?php
/**
 * @package AkarruCPE
 * @subpackage index
 * @version 0.6
 * @copyright (c) 2006 Eduardo Diaz Cortes
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License 
 * @author Eduardo Diaz <ediaz@lnds.net>
 */

require('akarru/common.php');
$page = request_value('page', 1);
$memes = new MemeList($page);
$template = new GridTemplate('master');
$template->set_data($memes);
$template->set_selector(0);
$template->display();
?>
