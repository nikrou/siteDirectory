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

if (!defined('DC_CONTEXT_ADMIN')) { exit; }

if (!empty($_SESSION['sd_message'])) {
  $message = $_SESSION['sd_message'];
  unset($_SESSION['sd_message']);
}

$sdm = new siteDirectoryManager($core);
$tm = new thematicManager($core);
$type_manager = new typeManager($core);

if (!empty($_REQUEST['action']) && in_array($_REQUEST['action'], array('add', 'edit'))) {
  $action = $_REQUEST['action'];
  if (!empty($_REQUEST['object']) && ($_REQUEST['object']=='thematic')) {
    include(dirname(__FILE__).'/inc/action_form_thematic.php');
  } elseif (!empty($_REQUEST['object']) && ($_REQUEST['object']=='type')) {
    include(dirname(__FILE__).'/inc/action_form_type.php');
  } else {
    include(dirname(__FILE__).'/inc/action_form_site.php');
  }
} else {
  include(dirname(__FILE__).'/inc/action_list.php');
}
