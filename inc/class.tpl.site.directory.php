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

class tplSiteDirectory
{
  public static function Thematics($attr, $content) {
    $res = '';

    $res .= "<?php\n";
    $res .= '$_ctx->thematics = $_ctx->sdm->getThematics(true);';
    $res .= 'while ($_ctx->thematics->fetch()):?>';
    $res .= $content;
    $res .= '<?php endwhile; $_ctx->thematics = null;?>';

    return $res;
  }

  public static function ThematicsHeader($attr, $content) {
    $res = '';

    $res .= '<?php if ($_ctx->thematics->isStart()):?>';
    $res .= $content;
    $res .= '<?php endif; ?>';

    return $res;
  }

  public static function ThematicsFooter($attr, $content) {
    $res = '';

    $res .= '<?php if ($_ctx->thematics->isEnd()):?>';
    $res .= $content;
    $res .= '<?php endif; ?>';

    return $res;
  }

  public static function ThematicLabel($attr) {
    $f = $GLOBALS['core']->tpl->getFilters($attr);

    return '<?php echo '.sprintf($f, '$_ctx->thematics->label').'; ?>';
  }

  public static function ThematicURL($attr) {
    $f = $GLOBALS['core']->tpl->getFilters($attr);

    return '<?php echo '.sprintf($f,'$core->blog->url.$core->url->getBase("thematic").'.
				 '"/".rawurlencode($_ctx->thematics->url)').'; ?>';
  }

  public static function ThematicClassname($attr) {
    $f = $GLOBALS['core']->tpl->getFilters($attr);

    $res = "<?php\n";
    $res .= 'if ("thematic"==$core->url->type && $_ctx->thematics->url==$_ctx->thematic->url) {';
    $res .=  'echo '.sprintf($f, '"selected ".$_ctx->thematics->classname').';';
    $res .= '}else{';
    $res .=  'echo '.sprintf($f, '$_ctx->thematics->classname').';';
    $res .= '}';
    $res .= '?>';
    
    return $res;
  }

  public static function ThematicPageLabel($attr) {
    $f = $GLOBALS['core']->tpl->getFilters($attr);

    $res = "<?php\n";
    $res .= 'if ("thematic"==$core->url->type) {';
    $res .= 'echo '.sprintf($f, '$_ctx->thematic->label').';';
    $res .= '} else {';
    $res .= 'echo '.sprintf($f, '$_ctx->sites->theme_label').';';
    $res .= '}?>';

    return $res;
  }

  // sites_directions
  public static function SitesDirections($attr, $content) {
    $res = '';

    $res .= "<?php\n";
    $res .= 'while ($_ctx->sites_directions->fetch()):?>';
    $res .= $content;
    $res .= '<?php endwhile; $_ctx->sites_directions = null; ?>';

    return $res;
  }

  public static function SitesSubthemes($attr, $content) {
    $res = '';

    $p = '';

    $p .= 'if ($_ctx->exists(\'sites_directions\')) {';
    $p .= '$params[\'direction\'] = $_ctx->sites_directions->direction;';
    $p .= '}';

    $p .= 'if ($_ctx->exists(\'thematic_url\')) {';
    $p .= '$params[\'thematic_url\'] = $_ctx->thematic_url;';
    $p .= '}';

    $res .= "<?php\n";
    $res .= $p;
    $res .= '$_ctx->subthemes = $_ctx->sdm->getSubthemes($params);unset($params);';
    $res .= 'while ($_ctx->subthemes->fetch()):?>';
    $res .= $content;
    $res .= '<?php endwhile; $_ctx->subthemes = null; ?>';
    
    return $res;
  }

  public static function SitesSubThemeLabel($attr) {
    $f = $GLOBALS['core']->tpl->getFilters($attr);

    return '<?php echo '.sprintf($f, '$_ctx->subthemes->label').'; ?>';
  }

  public static function SiteDirection($attr) {
    $f = $GLOBALS['core']->tpl->getFilters($attr);

    return '<?php echo '.sprintf($f, '__(siteDirectoryManager::getDirection($_ctx->sites_directions->direction)." area")').'; ?>';
  }

  // sites
  public static function Sites($attr, $content) {
    $res = '';
    $p = '';

    if (isset($attr['subtheme'])) {
      if ($attr['subtheme']==0) {
	$p .= "\$params['sql'] = ' AND s.subtheme IS NULL ';\n";
      } else {
	$p .= "\$params['sql'] = ' AND s.subtheme IS NOT NULL ';\n";
      }
    }

    $p .= 'if ($_ctx->exists(\'sites_directions\')) {';
    $p .= '$params[\'direction\'] = $_ctx->sites_directions->direction;';
    $p .= '}';

    $p .= 'if ($_ctx->exists(\'thematic\')) {';
    $p .= '$params[\'thematic\'] = $_ctx->thematic->url;';
    $p .= '}';

    $p .= 'if ($_ctx->exists(\'subthemes\')) {';
    $p .= '$params[\'subtheme\'] = $_ctx->subthemes->label;';
    $p .= '}';

    $res .= "<?php\n";
    $res .= $p;
    $res .= '$_ctx->sites = $_ctx->sdm->getSites($params);unset($params);';
    $res .= 'while ($_ctx->sites->fetch()):?>';
    $res .= $content;
    $res .= '<?php endwhile; $_ctx->sites = null; ?>';

    return $res;
  }

  public static function IfSiteImage($attr, $content) {
    return 
      '<?php if ($_ctx->sites->image_path):?>'. 
      $content.
      '<?php endif; ?>';
  }

  public static function SiteImage($attr, $content) {
    $f = $GLOBALS['core']->tpl->getFilters($attr);

    return '<?php echo '.sprintf($f, '$_ctx->sites->image_path').'; ?>';
  }

  public static function SiteThemeLabel($attr) {
    $f = $GLOBALS['core']->tpl->getFilters($attr);

    return '<?php echo '.sprintf($f, '$_ctx->sites->theme_label').'; ?>';
  }

  public static function SiteThemeURL($attr) {
    $f = $GLOBALS['core']->tpl->getFilters($attr);

    return '<?php echo '.sprintf($f,'$core->blog->url.$core->url->getBase("thematic").'.
				 '"/".rawurlencode($_ctx->sites->theme_url)').'; ?>';
  }

  public static function SiteSubThemeLabel($attr) {
    $f = $GLOBALS['core']->tpl->getFilters($attr);

    return '<?php echo '.sprintf($f, '$_ctx->sites->subtheme_label').'; ?>';
  }

  public static function SiteName($attr) {
    $f = $GLOBALS['core']->tpl->getFilters($attr);

    return '<?php echo '.sprintf($f, '$_ctx->sites->name').'; ?>';
  }

  public static function SiteAddress($attr) {
    $f = $GLOBALS['core']->tpl->getFilters($attr);

    return '<?php echo '.sprintf($f, '$_ctx->sites->address').'; ?>';
  }

  public static function SiteTown($attr) {
    $f = $GLOBALS['core']->tpl->getFilters($attr);

    return '<?php echo '.sprintf($f, '$_ctx->sites->town').'; ?>';
  }

  public static function SiteType($attr) {
    $f = $GLOBALS['core']->tpl->getFilters($attr);

    return '<?php echo '.sprintf($f, '$_ctx->sites->type').'; ?>';
  }

  public static function SiteDescription($attr) {
    $f = $GLOBALS['core']->tpl->getFilters($attr);

    return '<?php echo '.sprintf($f, '$_ctx->sites->description').'; ?>';
  }

  public static function SiteLongDescription($attr) {
    $f = $GLOBALS['core']->tpl->getFilters($attr);

    return '<?php echo '.sprintf($f, '$_ctx->sites->long_description').'; ?>';
  }

  public static function SiteEmail($attr) {
    $f = $GLOBALS['core']->tpl->getFilters($attr);
    
    return '<?php echo '.sprintf($f, '$_ctx->sites->email').'; ?>';
  }

  public static function SiteTelephone($attr) {
    $f = $GLOBALS['core']->tpl->getFilters($attr);
    
    return '<?php echo '.sprintf($f, '$_ctx->sites->telephone').'; ?>';
  }

  public static function SiteWebsite($attr) {
    $f = $GLOBALS['core']->tpl->getFilters($attr);

    return '<?php echo '.sprintf($f, '$_ctx->sites->website').'; ?>';
  }

  public static function SiteLatitude($attr) {
    $f = $GLOBALS['core']->tpl->getFilters($attr);

    return '<?php echo '.sprintf($f, '$_ctx->sites->latitude').'; ?>';
  }

  public static function SiteLongitude($attr) {
    $f = $GLOBALS['core']->tpl->getFilters($attr);

    return '<?php echo '.sprintf($f, '$_ctx->sites->longitude').'; ?>';
  }

  public static function SiteMapZoom($attr) {
    return $GLOBALS['core']->blog->settings->siteDirectory->map_zoom;
  }

  public static function IfSitePaid($attr, $content) {
    return 
      '<?php if ($_ctx->sites->paid):?>'. 
      $content.
      '<?php endif; ?>';
  }

  public static function IfSiteMobileDiffusion($attr, $content) {
    return 
      '<?php if ($_ctx->sites->mobile_diffusion):?>'. 
      $content.
      '<?php endif; ?>';
  }

  public static function SiteDirectionClassname($attr) {
    $f = $GLOBALS['core']->tpl->getFilters($attr);

    return '<?php echo '.sprintf($f, 'strtolower($_ctx->sites_directions->direction)').'; ?>';
  }

  public static function SiteURL($attr) {
    $f = $GLOBALS['core']->tpl->getFilters($attr);
    return '<?php echo '.sprintf($f,'$core->blog->url.$core->url->getBase("site").'.
				 '"/".rawurlencode($_ctx->sites->url)').'; ?>';
  }
}