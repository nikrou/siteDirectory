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

class siteDirectoryAdminPluginBehaviors
{
  public static function adminDashboardFavs($core, $favs) {
    $favs['sitedirectory'] = new ArrayObject(array('sitedirectory',
						   __('Site Directory'),
						   'plugin.php?p=siteDirectory',
						   'index.php?pf=siteDirectory/img/icon.png',
						   'index.php?pf=siteDirectory/img/icon-b.png',
						   'contentadmin',
						   null,
						   null));
  }
}
