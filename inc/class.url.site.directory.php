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

class urlSiteDirectory extends dcUrlHandlers
{
  public static function thematics($args) {
    self::serveDocument('thematics.html');
  }

  public static function thematic($args) {
    global $core, $_ctx;

    if (empty($args)) {
      throw new Exception('Page not found', 404);
    }

    $tm = new thematicManager($core);
    $_ctx->thematic = $tm->getThematicByURL($args);

    if ($_ctx->thematic->isEmpty()) {
      throw new Exception("Page not found", 404);
    }

    $_ctx->sites_directions = $_ctx->sdm->getSitesDirections($args);
    $_ctx->thematic_url = $args;

    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    self::serveDocument('thematic.html');
  }

  public static function site($args) {
    global $_ctx;

    if (empty($args)) {
      throw new Exception('Page not found', 404);
    }
    $_ctx->sites = $_ctx->sdm->getSiteByName($args);

    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    self::serveDocument('site.html');
  }
}
?>