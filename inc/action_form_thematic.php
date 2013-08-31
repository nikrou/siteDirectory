<?php
// +-----------------------------------------------------------------------+
// | Site Directory  - a plugin for dotclear                               |
// +-----------------------------------------------------------------------+
// | Copyright(C) 2011-2012 Nicolas Roudaire        http://www.nikrou.net  |
// +-----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify  |
// | it under the terms of the GNU General Public License version 2 as     |
// | published by the Free Software Foundation                             |
// |                                                                       |
// | This program is distributed in the hope that it will be useful, but   |
// | WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU      |
// | General Public License for more details.                              |
// |                                                                       |
// | You should have received a copy of the GNU General Public License     |
// | along with this program; if not, write to the Free Software           |
// | Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,            |
// | MA 02110-1301 USA.                                                    |
// +-----------------------------------------------------------------------+

$combo_thematics = array('' => '');
$rs = $tm->getList();
if (!$rs->isEmpty()) {
  while ($rs->fetch()) {
    $combo_thematics[$rs->label] = $rs->id;
  }
}

$page_title = __('New thematic');
$thematic = array('label' => '',
		  'description' => '',
		  'url' => '',
		  'position' => '',
		  'classname' => '',
		  'parent' => '');

if (($action=='edit') && !empty($_GET['id'])) {
  $rs_thematic = $tm->getThematic($_GET['id']);
  if (!$rs_thematic->isEmpty()) {
    $thematic['label'] = $rs_thematic->label;
    $thematic['description'] = $rs_thematic->description;
    $thematic['url'] = $rs_thematic->url;
    $thematic['position'] = $rs_thematic->position;
    $thematic['classname'] = $rs_thematic->classname;
    $thematic['parent'] = $rs_thematic->parent;
    $_SESSION['thematic_id'] = $_GET['id'];
  }
}

if (!empty($_POST['save_thematic']) && !empty($_POST['thematic_label'])) {
  $thematic['label'] = $_POST['thematic_label'];
  $thematic['description'] = isset($_POST['thematic_description'])?$_POST['thematic_description']:null;
  $thematic['position'] = isset($_POST['thematic_position'])?$_POST['thematic_position']:null;
  $thematic['classname'] = isset($_POST['thematic_classname'])?$_POST['thematic_classname']:null;
  $thematic['parent'] = isset($_POST['thematic_parent'])?$_POST['thematic_parent']:null;
  
  if ($action=='edit') {
    $method = 'update';
    $message = __('Thematic has been successfully updated.');
    $thematic['id'] = $_SESSION['thematic_id'];
    unset($_SESSION['thematic_id']);
  } else {
    $method = 'add';
    $message = __('Thematic has been successfully added.');
  }
  try {
    $tm->$method($thematic);
    $_SESSION['sd_message'] = $message;
    $_SESSION['sd_default_tab'] = 'thematics_list';
    http::redirect($p_url);
  } catch (Exception $e) {
    $core->error->add($e->getMessage());
  }
}

include(dirname(__FILE__).'/../tpl/form_thematic.tpl');
?>