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

if (!defined('DC_RC_PATH')) { return; }

if (!$core->blog->settings->siteDirectory->active) {
  return;
} else {
  $thematics_prefix = $core->blog->settings->siteDirectory->thematics_prefix;
  $thematic_prefix = $core->blog->settings->siteDirectory->thematic_prefix;
  $site_prefix = $core->blog->settings->siteDirectory->site_prefix;

  $core->url->register('thematics', $thematics_prefix, '^'.preg_quote($thematics_prefix).'$', array('urlSiteDirectory', 'thematics'));
  $core->url->register('thematic', $thematic_prefix, '^'.preg_quote($thematic_prefix).'/(.+)$', array('urlSiteDirectory', 'thematic'));
  $core->url->register('site', $site_prefix, '^'.preg_quote($site_prefix).'/(.+)$', array('urlSiteDirectory', 'site'));

  $_ctx->sdm = new siteDirectoryManager($core);

  $core->addBehavior('publicHeadContent', array('behaviorsSiteDirectory', 'publicHeadContent'));
  $core->addBehavior('publicBeforeDocument', array('behaviorsSiteDirectory', 'addTplPath'));

  $core->tpl->addBlock('Thematics', array('tplSiteDirectory', 'Thematics'));
  $core->tpl->addBlock('ThematicsHeader', array('tplSiteDirectory', 'ThematicsHeader'));
  $core->tpl->addBlock('ThematicsFooter', array('tplSiteDirectory', 'ThematicsFooter'));
  $core->tpl->addValue('ThematicLabel', array('tplSiteDirectory', 'ThematicLabel'));
  $core->tpl->addValue('ThematicClassname', array('tplSiteDirectory', 'ThematicClassname'));
  $core->tpl->addValue('ThematicURL', array('tplSiteDirectory', 'ThematicURL'));

  $core->tpl->addBlock('Sites', array('tplSiteDirectory', 'Sites'));
  $core->tpl->addBlock('SitesDirections', array('tplSiteDirectory', 'SitesDirections'));
  $core->tpl->addBlock('SitesSubThemes', array('tplSiteDirectory', 'SitesSubThemes'));
  $core->tpl->addValue('SitesSubThemeLabel', array('tplSiteDirectory', 'SitesSubThemeLabel'));
  $core->tpl->addValue('SiteDirection', array('tplSiteDirectory', 'SiteDirection'));
  $core->tpl->addValue('ThematicPageLabel', array('tplSiteDirectory', 'ThematicPageLabel'));
  $core->tpl->addValue('SiteURL', array('tplSiteDirectory', 'SiteURL'));

  $core->tpl->addBlock('IfSiteImage', array('tplSiteDirectory', 'IfSiteImage'));
  $core->tpl->addValue('SiteImage', array('tplSiteDirectory', 'SiteImage'));
  $core->tpl->addValue('SiteName', array('tplSiteDirectory', 'SiteName'));
  $core->tpl->addValue('SiteThemeLabel', array('tplSiteDirectory', 'SiteThemeLabel'));
  $core->tpl->addValue('SiteThemeURL', array('tplSiteDirectory', 'SiteThemeURL'));
  $core->tpl->addValue('SiteSubThemeLabel', array('tplSiteDirectory', 'SiteSubThemeLabel'));
  $core->tpl->addValue('SiteAddress', array('tplSiteDirectory', 'SiteAddress'));
  $core->tpl->addValue('SiteTown', array('tplSiteDirectory', 'SiteTown'));
  $core->tpl->addValue('SiteType', array('tplSiteDirectory', 'SiteType'));
  $core->tpl->addValue('SiteDescription', array('tplSiteDirectory', 'SiteDescription'));
  $core->tpl->addValue('SiteLongDescription', array('tplSiteDirectory', 'SiteLongDescription'));
  $core->tpl->addValue('SiteEmail', array('tplSiteDirectory', 'SiteEmail'));
  $core->tpl->addValue('SiteTelephone', array('tplSiteDirectory', 'SiteTelephone'));
  $core->tpl->addValue('SiteWebsite', array('tplSiteDirectory', 'SiteWebsite'));
  $core->tpl->addValue('SiteDirectionClassname', array('tplSiteDirectory', 'SiteDirectionClassname'));
  $core->tpl->addValue('SiteLatitude', array('tplSiteDirectory', 'SiteLatitude'));
  $core->tpl->addValue('SiteLongitude', array('tplSiteDirectory', 'SiteLongitude'));
  $core->tpl->addValue('SiteMapZoom', array('tplSiteDirectory', 'SiteMapZoom'));
  $core->tpl->addBlock('IfSitePaid', array('tplSiteDirectory', 'IfSitePaid'));
  $core->tpl->addBlock('IfSiteMobileDiffusion', array('tplSiteDirectory', 'IfSiteMobileDiffusion'));
}
