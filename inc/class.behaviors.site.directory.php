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

class behaviorsSiteDirectory
{
  public static function publicHeadContent($core) {
    if (!$core->blog->settings->siteDirectory->active) {
      return;
    }

    if ($core->url->type=='site') {
      printf('<link type="text/css" rel="stylesheet" href="%s"/>'."\n",
         $core->blog->getQmarkURL().'pf=siteDirectory/css/site.directory.css'
         );
      echo '<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>'."\n";
      printf('<script type="text/javascript" src="%s"></script>'."\n",
         $core->blog->getQmarkURL().'pf=siteDirectory/js/jquery.site.directory.map.js'
         );
    }

    printf('<script type="text/javascript" src="%s"></script>'."\n",
       $core->blog->getQmarkURL().'pf=siteDirectory/js/jquery.site.directory.js'
       );
  }

  public static function addTplPath($core) {
    $core->tpl->setPath($core->tpl->getPath(), dirname(__FILE__).'/../default-templates');
  }
}
