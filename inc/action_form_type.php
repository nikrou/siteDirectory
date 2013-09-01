<?php
// +-----------------------------------------------------------------------+
// | Site Directory  - a plugin for dotclear                               |
// +-----------------------------------------------------------------------+
// | Copyright(C) 2011-2013 Nicolas Roudaire        http://www.nikrou.net  |
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

$page_title = __('New type');
$type = array('label' => '');

if (($action=='edit') && !empty($_GET['id'])) {
  $rs_type = $type_manager->getType($_GET['id']);
  if (!$rs_type->isEmpty()) {
    $type['label'] = $rs_type->label;
    $_SESSION['type_id'] = $_GET['id'];
  }
}

if (!empty($_POST['save_type']) && !empty($_POST['type_label'])) {
  $type['label'] = $_POST['type_label'];

  if ($action=='edit') {
    $method = 'update';
    $message = __('Type has been successfully updated.');
    $type['id'] = $_SESSION['type_id'];
    unset($_SESSION['type_id']);
  } else {
    $method = 'add';
    $message = __('Type has been successfully added.');
  }
  try {
    $type_manager->$method($type);
    $_SESSION['sd_message'] = $message;
    $_SESSION['sd_default_tab'] = 'types_list';
    http::redirect($p_url);
  } catch (Exception $e) {
    $core->error->add($e->getMessage());
  }
}

include(dirname(__FILE__).'/../tpl/form_type.tpl');
