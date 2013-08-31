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

$version = $core->plugins->moduleInfo('siteDirectory', 'version');
if (version_compare($core->getVersion('siteDirectory'), $version,'>=')) {
  return;
}

$settings = $core->blog->settings;
$settings->addNamespace('siteDirectory');

$settings->siteDirectory->put('active', false, 'boolean', 'site Directory plugin activated ?', false);
$settings->siteDirectory->put('thematics_prefix', 'thematics', 'string', 'Thematics page prefix', true);
$settings->siteDirectory->put('thematic_prefix', 'thematic', 'string', 'Thematic page prefix', true);
$settings->siteDirectory->put('site_prefix', 'site', 'string', 'Site page prefix', true);
$settings->siteDirectory->put('map_zoom', 13, 'float', 'Map zoom level', true);

$s = new dbStruct($core->con, $core->prefix);
$s->site_directory
->id ('bigint',	0, false)
->blog_id ('varchar', 32, false)
->theme('bigint', 0, false)
->subtheme('bigint', 0, true, null)
->name('varchar', 255, false)
->url('varchar', 255, true, null)
->description('text', 0, true, null)
->long_description('text', 0, true, null)
->address('text', 0, true, null)
->email('varchar', 255, true, null)
->telephone('varchar', 255, true, null)
->type('bigint', 0, true, null)
->media_id('bigint', 0,	true, null)
->direction('varchar', 10, true, null)
->town('varchar', 255, true, null)
->latitude('varchar', 255, true, null)
->longitude('varchar', 255, true, null)
->paid('smallint', 0,  true, 0)
->mobile_diffusion('smallint', 0,  true, 0)
->website('varchar', 255, true, null)

->status('smallint', 0, true, -2)
->position('integer', 0, true, 0)
->created_at('timestamp', 0, false, 'now()')
->updated_at('timestamp', 0, false, 'now()')

->primary('pk_site_directory', 'id');

$s->sd_thematic
->id ('bigint',	0, false)
->blog_id ('varchar', 32, false)
->label('varchar', 255, false)
->url('varchar', 255, true, null)
->classname('varchar', 255, true, null)
->description('text', 0, true, null)
->position('integer', 0, true, 0)
->parent('bigint', 0, true, 0)

->primary('pk_sd_thematic', 'id');

$s->sd_type
->id ('bigint',	0, false)
->blog_id ('varchar', 32, false)
->label('varchar', 255, false)
->primary('pk_sd_type', 'id');

$si = new dbStruct($core->con, $core->prefix);
$changes = $si->synchronize($s);

$core->setVersion('siteDirectory', $version);
return true;
?>
