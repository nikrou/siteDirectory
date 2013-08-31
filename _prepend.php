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

if (!defined('DC_RC_PATH')) { return; }

if ($core->blog->settings->siteDirectory->active) {
  $thematics_prefix = $core->blog->settings->siteDirectory->thematics_prefix;
  $thematic_prefix = $core->blog->settings->siteDirectory->thematic_prefix;
  $site_prefix = $core->blog->settings->siteDirectory->site_prefix;

  $core->url->register('thematics', $thematics_prefix, '^'.preg_quote($thematics_prefix).'$', array('urlSiteDirectory', 'thematics'));
  $core->url->register('thematic', $thematic_prefix, '^'.preg_quote($thematic_prefix).'/(.+)$', array('urlSiteDirectory', 'thematic'));
  $core->url->register('site', $site_prefix, '^'.preg_quote($site_prefix).'/(.+)$', array('urlSiteDirectory', 'site'));
}

$__autoload['siteDirectoryAdminPluginBehaviors'] = dirname(__FILE__).'/inc/class.admin.plugin.behaviors.php';
$__autoload['siteDirectoryManager'] = dirname(__FILE__).'/inc/class.site.directory.manager.php'; 
$__autoload['adminSiteDirectoryList'] = dirname(__FILE__).'/inc/class.admin.site.directory.list.php'; 
$__autoload['thematicManager'] = dirname(__FILE__).'/inc/class.thematic.manager.php';
$__autoload['typeManager'] = dirname(__FILE__).'/inc/class.type.manager.php';

$__autoload['behaviorsSiteDirectory'] = dirname(__FILE__).'/inc/class.behaviors.site.directory.php'; 
$__autoload['urlSiteDirectory'] = dirname(__FILE__).'/inc/class.url.site.directory.php'; 
$__autoload['tplSiteDirectory'] = dirname(__FILE__).'/inc/class.tpl.site.directory.php'; 

$__autoload['siteDirectoryRestMethods'] = dirname(__FILE__).'/inc/class.site.directory.rest.methods.php';

$core->rest->addFunction('removeSiteMedia', array('siteDirectoryRestMethods', 'removeSiteMedia'));
?>