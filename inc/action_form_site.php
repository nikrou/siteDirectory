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

$page_title = __('New site');

$site = array('theme' => '', 'subtheme' => '', 'name' => '', 'url' => '', 'description' => '',
              'long_description' => '', 'address' => '', 'telephone' => '', 'email' => '',
              'type' => '', 'direction' => '', 'town' => '', 'latitude' => '', 'longitude' => '',
              'paid' => '', 'mobile_diffusion' => '', 'website' => ''
              );

$site_directory_theme = '';
$site_directory_subtheme = '';
$sitedirectory_media_subdirectory = $core->blog->settings->siteDirectory->media_subdirectory;

$combo_status = array_merge(array('' => ''), array_flip(siteDirectoryManager::geti18nStatus()));
$combo_theme = array('' => '');
$combo_subtheme = array('' => '');
$list_subthemes = array();

$rs_thematics = $sdm->getThematics();
if (!$rs_thematics->isEmpty()) {
  while ($rs_thematics->fetch()) {
    if ($rs_thematics->parent==0) {
      $combo_theme[$rs_thematics->label] = $rs_thematics->id;
    } else {
      $list_subthemes[$rs_thematics->parent][] = array('label' => $rs_thematics->label,
                                                       'id' => $rs_thematics->id
                                                       );
    }
  }
}

$site_directory_subtheme_js = 'var site_directory_subthemes = [];';
foreach ($list_subthemes as $ref_id => $subthemes) {
  $site_directory_subtheme_js .= sprintf('site_directory_subthemes[%d] = [', $ref_id);
  foreach ($subthemes as $subtheme) {
    $site_directory_subtheme_js .= sprintf("{id:%d, label:'%s'},",
                       htmlentities($subtheme['id'], ENT_QUOTES, 'utf-8'),
                       htmlentities($subtheme['label'], ENT_QUOTES, 'utf-8')
                       );
  }
  $site_directory_subtheme_js = substr($site_directory_subtheme_js, 0, -1);
  $site_directory_subtheme_js .= '];';
}

$combo_direction = array_merge(array('' => ''), array_flip(siteDirectoryManager::geti18nDirections()));
$combo_type = array('' => '');
$rs_types = $type_manager->getList();
if (!$rs_types->isEmpty()) {
  while ($rs_types->fetch()) {
    $combo_type[$rs_types->label] = $rs_types->id;
  }
}

$p_url_action = $p_url.'&amp;action='.$action;

if (!empty($_REQUEST['id'])) {
  if ($action=='edit') {
    $page_title = __('Edit site');
  }

  $rs = $sdm->getSite($_REQUEST['id']);
  if (!$rs->isEmpty()) {
    $site['id'] = $_REQUEST['id'];
    $site['url'] = $rs->url;
    $site['theme'] = $rs->theme;
    $site['subtheme'] = $rs->subtheme;
    $site['name'] = $rs->name;
    $site['description'] = $rs->description;
    $site['long_description'] = $rs->long_description;
    $site['address'] = $rs->address;
    $site['telephone'] = $rs->telephone;
    $site['email'] = $rs->email;
    $site['type'] = $rs->type;
    $site['media_id'] = $rs->media_id;
    $site['direction'] = $rs->direction;
    $site['town'] = $rs->town;
    $site['latitude'] = $rs->latitude;
    $site['longitude'] = $rs->longitude;
    $site['paid'] = $rs->paid;
    $site['mobile_diffusion'] = $rs->mobile_diffusion;
    $site['website'] = $rs->website;
    $_SESSION['site_id'] = $_REQUEST['id'];

    if (!empty($site['media_id'])) {
      $m = new dcMedia($core);
      $media_file = $m->getFile($site['media_id']);

      $site['media_file']['media_icon'] = $media_file->media_icon;
      $site['media_file']['media_name'] = $media_file->basename;
      $site['media_file']['link'] = 'media_item.php?id='.$media_file->media_id.'&amp;d='.$sitedirectory_media_subdirectory;
      $site['media_file']['delete_url'] = $p_url.'&amp;action=delete_media&amp;id='.$media_file->media_id;
    }
  } else {
    throw new Exception(__('That site doesn\'t exist'));
  }
}

if (!empty($site['theme']) && isset($list_subthemes[$site['theme']])) {
  foreach ($list_subthemes[$site['theme']] as $subtheme) {
    $combo_subtheme[$subtheme['label']] = $subtheme['id'];
  }
}

if (!empty($_POST['save_site_directory'])) {
  if (!empty($_FILES['site_directory_image']['tmp_name'])) {
    $core->media = new dcMedia($core);
    $core->media->chdir($sitedirectory_media_subdirectory);

    try {
      files::uploadStatus($_FILES['site_directory_image']);
      $site['media_id'] = $core->media->uploadFile($_FILES['site_directory_image']['tmp_name'],
                           $_FILES['site_directory_image']['name'],
                           '',
                           false,
                           true
                           );
    } catch (Exception $e) {
      $core->error->add($e->getMessage());
    }
  }

  $site['theme'] = $_POST['site_directory_theme'];
  $site['subtheme'] = isset($_POST['site_directory_subtheme'])?$_POST['site_directory_subtheme']:'';
  $site['name'] = $_POST['site_directory_name'];
  if (isset($_POST['site_directory_url'])) {
    $site['url'] = $_POST['site_directory_url'];
  }
  $site['description'] = $_POST['site_directory_description'];
  $site['long_description'] = $_POST['site_directory_long_description'];
  $site['address'] = $_POST['site_directory_address'];
  $site['telephone'] = $_POST['site_directory_telephone'];
  $site['email'] = $_POST['site_directory_email'];
  $site['type'] = $_POST['site_directory_type'];
  $site['direction'] = $_POST['site_directory_direction'];
  $site['town'] = $_POST['site_directory_town'];
  $site['latitude'] = $_POST['site_directory_latitude'];
  $site['longitude'] = $_POST['site_directory_longitude'];
  $site['paid'] = isset($_POST['site_directory_paid'])?1:0;
  $site['mobile_diffusion'] = isset($_POST['site_directory_mobile_diffusion'])?1:0;
  if (!empty($_POST['site_directory_website'])) {
    $site['website'] = 'http://'.str_replace('http://', '', trim($_POST['site_directory_website']));
  }

  if ($action=='edit') {
    $method = 'update';
    $message = __('Site directory has been successfully updated.');
    $site['id'] = $_SESSION['site_id'];
    unset($_SESSION['site_id']);
  } else {
    $method = 'add';
    $message = __('Site directory has been successfully added.');
  }

  try {
    $sdm->$method($site);
    $_SESSION['sd_message'] = $message;
    http::redirect($p_url);
  } catch (Exception $e) {
    $core->error->add($e->getMessage());
  }
}

dcPage::helpBlock('site');

include(dirname(__FILE__).'/../tpl/form_site.tpl');
