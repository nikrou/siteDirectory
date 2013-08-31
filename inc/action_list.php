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

if (!defined('DC_CONTEXT_ADMIN')) { exit; }

$is_super_admin = $core->auth->isSuperAdmin();
$default_tab = 'sitedirectory_settings';

$core->blog->settings->addNameSpace('siteDirectory');
$sitedirectory_active = $core->blog->settings->siteDirectory->active;
$sitedirectory_thematics_prefix = $core->blog->settings->siteDirectory->thematics_prefix;
$sitedirectory_thematic_prefix = $core->blog->settings->siteDirectory->thematic_prefix;
$sitedirectory_site_prefix = $core->blog->settings->siteDirectory->site_prefix;

$sitedirectory_media_subdirectory = $core->blog->settings->siteDirectory->media_subdirectory;
$sitedirectory_map_zoom = $core->blog->settings->siteDirectory->map_zoom;

/* types */
try {
  $types = $type_manager->getList();
} catch (Exception $e) {
  $core->error->add($e->getMessage());
}


/* thematics */
try {
  $rs_thematic = $tm->getList();
  $thematics = array();
  while ($rs_thematic->fetch()) {
    if ($rs_thematic->parent==0) {
      $thematics[$rs_thematic->id] = array('id' => $rs_thematic->id, 
					   'label' => $rs_thematic->label, 
					   'position' => $rs_thematic->position,
					   'classname' => $rs_thematic->classname,
					   'children' => array()
					   );
    } else {
      $thematics[$rs_thematic->parent]['children'][$rs_thematic->id] = array('id' => $rs_thematic->id, 'label' => $rs_thematic->label, 'parent_label' => $rs_thematic->parent_label, 'position' => $rs_thematic->position);
    }
  }
} catch (Exception $e) {
  $core->error->add($e->getMessage());
}

/* list sites */
$page = !empty($_GET['page']) ? (integer) $_GET['page'] : 1;
$nb_per_page =  30;

if (!empty($_GET['nb']) && (integer) $_GET['nb'] > 0) {
  $nb_per_page = (integer) $_GET['nb'];
}

$combo_action = array();
$combo_action[__('publish')] = 'publish';
$combo_action[__('unpublish')] = 'unpublish';
$combo_action[__('mark as pending')] = 'pending';
$combo_action[__('delete')] = 'delete';

try {
  $sites_list = new adminSiteDirectoryList($core, $sdm->getList(), $nb_per_page);
} catch (Exception $e) {
  $core->error->add($e->getMessage());
}

if ($core->auth->isSuperAdmin() && !empty($_POST['action']) 
    && in_array($_POST['action'], array_values($combo_action)) && !empty($_POST['sites'])) {
  try {
    $sdm->updateStatus($_POST['sites'], $_POST['action']);
    $_SESSION['sd_message'] = __('Status for theses sites have been successfully updated.');
    http::redirect($p_url);  
  } catch (Exception $e) {
    $core->error->add($e->getMessage());
  }
} elseif ($core->auth->isSuperAdmin() && !empty($_POST['do_remove']) 
	  && ($_REQUEST['object']=='thematic') && !empty($_POST['thematics'])) {
  try {
    $tm->delete($_POST['thematics']);
    $_SESSION['sd_message'] = __('Thematic(s) successfully deleted.');
    $_SESSION['sd_default_tab'] = 'thematics_list';
    http::redirect($p_url);
  } catch (Exception $e) {
    $core->error->add($e->getMessage());
  }
} elseif ($core->auth->isSuperAdmin() && !empty($_POST['do_remove']) 
	  && ($_REQUEST['object']=='type') && !empty($_POST['types'])) {
  try {
    $type_manager->delete($_POST['types']);
    $_SESSION['sd_message'] = __('Type(s) successfully deleted.');
    $_SESSION['sd_default_tab'] = 'types_list';
    http::redirect($p_url);
  } catch (Exception $e) {
    $core->error->add($e->getMessage());
  }
} elseif (!empty($_POST['save_order'])) {
  if ($_REQUEST['object']=='thematic') {
    try {
      $tm->updatePositions($_POST['thematic_position']);
      $_SESSION['sd_message'] = __('Order for thematics has been successfully updated.');
      $_SESSION['sd_default_tab'] = 'thematics_list';
      http::redirect($p_url);    
    } catch (Exception $e) {
      $core->error->add($e->getMessage());
    }
  } else {
    try {
      $sdm->updatePositions($_POST['site_position']);
      $_SESSION['sd_message'] = __('Order for sites has been successfully updated.');
      http::redirect($p_url);    
    } catch (Exception $e) {
      $core->error->add($e->getMessage());
    }
  }
}

if ($core->auth->isSuperAdmin() && !empty($_POST['saveconfig'])) {
  try {
    $sitedirectory_active = (empty($_POST['sitedirectory_active']))?false:true;
    $core->blog->settings->siteDirectory->put('active', $sitedirectory_active, 'boolean');

    if (!empty($_POST['sitedirectory_map_zoom'])) {
      $sitedirectory_map_zoom = (float) $_POST['sitedirectory_map_zoom'];
      $core->blog->settings->siteDirectory->put('map_zoom', $sitedirectory_map_zoom, 'float');      
    }    

    if (!empty($_POST['sitedirectory_media_subdirectory'])) {
      $sitedirectory_media_subdirectory = trim($_POST['sitedirectory_media_subdirectory']);
    } else {
      $sitedirectory_media_subdirectory = 'site_directory';
    }
    files::makeDir($core->blog->public_path.'/'.$sitedirectory_media_subdirectory);
    $core->blog->settings->siteDirectory->put('media_subdirectory', $sitedirectory_media_subdirectory, 'string');

    if (!empty($_POST['sitedirectory_thematics_prefix'])) {
      $sitedirectory_thematics_prefix = trim($_POST['sitedirectory_thematics_prefix']);
    } else {
      $sitedirectory_thematics_prefix = 'thematics';
    }
    $core->blog->settings->siteDirectory->put('thematics_prefix', $sitedirectory_thematics_prefix, 'string');

    if (!empty($_POST['sitedirectory_thematic_prefix'])) {
      $sitedirectory_thematic_prefix = trim($_POST['sitedirectory_thematic_prefix']);
    } else {
      $sitedirectory_thematic_prefix = 'thematic';
    }
    $core->blog->settings->siteDirectory->put('thematic_prefix', $sitedirectory_thematic_prefix, 'string');

    if (!empty($_POST['sitedirectory_site_prefix'])) {
      $sitedirectory_site_prefix = trim($_POST['sitedirectory_site_prefix']);
    } else {
      $sitedirectory_site_prefix = 'site';
    }
    $core->blog->settings->siteDirectory->put('site_prefix', $sitedirectory_site_prefix, 'string');

    $message = __('Configuration successfully updated.');
  } catch(Exception $e) {
    $core->error->add($e->getMessage());
  }
}

if ($sitedirectory_active) {
  if (!empty($_SESSION['sd_default_tab'])) {
    $default_tab = $_SESSION['sd_default_tab'];
    unset($_SESSION['sd_default_tab']);
  } else {
    $default_tab = 'sitedirectory_list';
  }
} else {
  $default_tab = 'sitedirectory_settings';
}

include(dirname(__FILE__).'/../tpl/index.tpl');
?>