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

class siteDirectoryManager
{
  public static
    $Directions = array('N' => 'North', 'S' => 'South', 'E' => 'East', 'W' => 'West'),
    $Status = array('-2' => 'pending', '0' => 'unpublished', '1' => 'published'),
    $Status_action = array('publish' => 1, 'unpublish' => 0, 'pending' => -2, 'delete' => -99);

  public function __construct($core) {
    $this->core = $core;
    $this->blog = $core->blog;
    $this->con = $this->blog->con;
    $this->table_site = $this->blog->prefix . 'site_directory';
    $this->table_theme = $this->blog->prefix . 'sd_thematic';
    $this->table_type = $this->blog->prefix . 'sd_type';
  }

  public function getList() {
    $strReq =  'SELECT s.id, s.position, theme, t1.label AS theme_label, subtheme, t2.label AS subtheme_label,';
    $strReq .=  'name, s.description, s.long_description, address, email, telephone, tt.label as type,';
    $strReq .=  'direction, town, latitude, longitude,';
    $strReq .=  'paid, mobile_diffusion, website, status';
    $strReq .= ' FROM '.$this->table_site.' s';
    $strReq .= ' LEFT JOIN '.$this->table_theme.' AS t1 ON theme=t1.id';
    $strReq .= ' LEFT JOIN '.$this->table_theme.' AS t2 ON subtheme=t2.id';
    $strReq .= ' LEFT JOIN '.$this->table_type.' AS tt ON s.type=tt.id';
    $strReq .= ' WHERE s.blog_id = \''.$this->con->escape($this->blog->id).'\'';
    if (!defined('DC_CONTEXT_ADMIN')) {
      $strReq .= ' AND status = 1';
    }
    $strReq .= ' ORDER BY s.position ASC, created_at ASC';

    $rs = $this->con->select($strReq);
    $rs = $rs->toStatic();

    return $rs;
  }

  public function getSitesDirections($thematic) {
    $strReq =  'SELECT s.direction';
    $strReq .= ' FROM '.$this->table_site.' AS s';
    $strReq .= ' LEFT JOIN '.$this->table_theme.' AS t1 ON theme=t1.id';
    $strReq .= ' WHERE s.blog_id = \''.$this->con->escape($this->blog->id).'\'';
    $strReq .= ' AND t1.url = \''.$this->con->escape($thematic).'\'';
    $strReq .= ' AND status = 1';
    $strReq .= ' GROUP BY s.direction';
    $strReq .= ' ORDER BY s.direction=\'N\' desc, s.direction=\'E\' desc, s.direction=\'S\' desc, s.direction=\'W\' desc';

    $rs = $this->con->select($strReq);
    $rs = $rs->toStatic();

    return $rs;        
  }

  public function getSubthemes(array $params=array()) {
    $strReq = 'SELECT distinct t2.label';
    $strReq .= ' FROM '.$this->table_site.' AS s';
    $strReq .= ' LEFT JOIN '.$this->table_theme.' AS t1 ON theme=t1.id';
    $strReq .= ' LEFT JOIN '.$this->table_theme.' AS t2 ON subtheme=t2.id';
    $strReq .= ' WHERE s.blog_id = \''.$this->con->escape($this->blog->id).'\'';
    $strReq .= ' AND t2.parent != 0';
    $strReq .= ' AND s.status = 1';

    if (isset($params['direction'])) {
      $strReq .= ' AND s.direction = \''.$this->con->escape($params['direction']).'\'';
    }
    if (isset($params['thematic_url'])) {
      $strReq .= ' AND t1.url = \''.$this->con->escape($params['thematic_url']).'\'';
    }

    $rs = $this->con->select($strReq);
    $rs = $rs->toStatic();

    return $rs;        
  }

  public function getSites(array $params=array()) {
    $strReq =  'SELECT s.id, theme, t1.label AS theme_label, subtheme, t2.label AS subtheme_label,';
    $strReq .=  'name, s.url, s.description, s.long_description, address, email, telephone, tt.label as type,';
    $strReq .=  'direction, town, latitude, longitude,';
    $strReq .=  'paid, mobile_diffusion, website, status';
    $strReq .= ' FROM '.$this->table_site.' s';
    $strReq .= ' LEFT JOIN '.$this->table_theme.' AS t1 ON theme=t1.id';
    $strReq .= ' LEFT JOIN '.$this->table_theme.' AS t2 ON subtheme=t2.id';
    $strReq .= ' LEFT JOIN '.$this->table_type.' AS tt ON s.type=tt.id';
    $strReq .= ' WHERE s.blog_id = \''.$this->con->escape($this->blog->id).'\'';
    $strReq .= ' AND s.status = 1';

    if (isset($params['direction'])) {
      $strReq .= ' AND s.direction = \''.$this->con->escape($params['direction']).'\'';
    }
    if (isset($params['thematic'])) {
      $strReq .= ' AND t1.url = \''.$this->con->escape($params['thematic']).'\'';
    }
    if (!empty($params['subtheme'])) {
      $strReq .= ' AND t2.label = \''.$this->con->escape($params['subtheme']).'\'';
    }

    if (isset($params['sql'])) {
      $strReq .= $params['sql'];
    }

    $strReq .= ' ORDER BY s.position ASC, created_at ASC';

    $rs = $this->con->select($strReq);
    $rs = $rs->toStatic();

    return $rs;    
  }

  public function getSitesByThematic($thematic) {
    $strReq =  'SELECT s.id, theme, t1.label AS theme_label, subtheme, t2.label AS subtheme_label,';
    $strReq .=  'name, s.url, s.description, s.long_description, address, email, telephone, tt.label as type,';
    $strReq .=  'direction, town, latitude, longitude,';
    $strReq .=  'paid, mobile_diffusion, website, status';
    $strReq .= ' FROM '.$this->table_site.' s';
    $strReq .= ' LEFT JOIN '.$this->table_theme.' AS t1 ON theme=t1.id';
    $strReq .= ' LEFT JOIN '.$this->table_theme.' AS t2 ON subtheme=t2.id';
    $strReq .= ' LEFT JOIN '.$this->table_type.' AS tt ON s.type=tt.id';
    $strReq .= ' WHERE s.blog_id = \''.$this->con->escape($this->blog->id).'\'';
    $strReq .= ' AND t1.url = \''.$this->con->escape($thematic).'\'';
    $strReq .= ' AND status = 1';
    $strReq .= ' ORDER BY s.direction=\'N\' desc, s.direction=\'E\' desc, s.direction=\'S\' desc, s.direction=\'W\' desc,';
    $strReq .= ' s.position ASC, created_at ASC';

    $rs = $this->con->select($strReq);
    $rs = $rs->toStatic();

    return $rs;    
  }

  public function getThematics($only_parents=false) {
    $strReq =  'SELECT t1.id, t1.label, t1.position, t1.url, t1.classname, t1.parent, t2.label as parent_label';
    $strReq .= ' FROM '.$this->table_theme.' AS t1';
    $strReq .= ' LEFT JOIN '.$this->table_theme.' AS t2 ON t1.parent=t2.id';
    $strReq .= ' WHERE t1.blog_id = \''.$this->con->escape($this->blog->id).'\'';
    if ($only_parents) {
      $strReq .= ' AND t1.parent=0';
    }
    $strReq .= ' ORDER BY t1.parent ASC, t1.position ASC';

    $rs = $this->con->select($strReq);
    $rs = $rs->toStatic();

    return $rs;
  }

  public function add($site) {
    $cur = $this->con->openCursor($this->table_site);
    if (empty($site['theme'])) {
      throw new Exception(__('You must provide a theme'));
    }
    if (empty($site['name'])) {
      throw new Exception(__('You must provide a name'));
    }
    if (empty($site['description'])) {
      throw new Exception(__('You must provide a description'));
    }
    if (empty($site['address'])) {
      throw new Exception(__('You must provide an address'));
    }
    if (empty($site['type'])) {
      throw new Exception(__('You must provide a type'));
    }
    if (empty($site['direction'])) {
      throw new Exception(__('You must provide a direction'));
    }
    if (empty($site['town'])) {
      throw new Exception(__('You must provide a town'));
    }
    if (empty($site['latitude'])) {
      throw new Exception(__('You must provide a latitude'));
    }
    if (empty($site['longitude'])) {
      throw new Exception(__('You must provide a longitude'));
    }
    $cur->blog_id = (string) $this->blog->id;
    $cur->theme = (int) $site['theme'];
    $cur->subtheme = !empty($site['subtheme'])?(int) $site['subtheme']:null;
    if (!empty($site['name'])) {
      $cur->name = (string) $site['name'];
    }
    $cur->url = $this->getSiteDirectoryURL(null, $site['name']);

    if (!empty($site['description'])) {
      $cur->description = (string) $site['description'];
    }
    if (!empty($site['long_description'])) {
      $cur->long_description = (string) $site['long_description'];
    }
    if (!empty($site['address'])) {
      $cur->address = (string) $site['address'];
    }
    if (!empty($site['telephone'])) {
      $cur->telephone = (string) $site['telephone'];
    }
    if (!empty($site['email'])) {
      $cur->email = (string) $site['email'];
    }
    if (!empty($site['type'])) {
      $cur->type = (int) $site['type'];
    }
    if (!empty($site['media_id'])) {
      $cur->media_id = (int) $site['media_id'];
    }
    if (!empty($site['direction'])) {
      $cur->direction = (string) $site['direction'];
    }
    if (!empty($site['town'])) {
      $cur->town = (string) $site['town'];
    }
    if (!empty($site['latitude'])) {
      $cur->latitude = (string) $site['latitude'];
    }
    if (!empty($site['longitude'])) {
      $cur->longitude = (string) $site['longitude'];
    }
    if (!empty($site['paid'])) {
      $cur->paid = (int) $site['paid'];
    }
    if (!empty($site['mobile_diffusion'])) {
      $cur->mobile_diffusion = (int) $site['mobile_diffusion'];
    }
    if (!empty($site['website'])) {
      $cur->website = (string) $site['website'];
    } else {
      $cur->website = null;
    }

    $strReq = 'SELECT MAX(id) FROM '.$this->table_site;
    $rs = $this->con->select($strReq);
    $cur->id = (integer) $rs->f(0) + 1;
		
    $cur->insert();
    $this->blog->triggerBlog();
  }

  public function update($site) {
    $cur = $this->con->openCursor($this->table_site);

    if (empty($site['theme'])) {
      throw new Exception(__('You must provide a theme'));
    }
    if (empty($site['name'])) {
      throw new Exception(__('You must provide a name'));
    }
    if (empty($site['description'])) {
      throw new Exception(__('You must provide a description'));
    }
    if (empty($site['address'])) {
      throw new Exception(__('You must provide a address'));
    }
    if (empty($site['type'])) {
      throw new Exception(__('You must provide a type'));
    }
    if (empty($site['direction'])) {
      throw new Exception(__('You must provide a direction'));
    }
    if (empty($site['town'])) {
      throw new Exception(__('You must provide a town'));
    }
    if (empty($site['latitude'])) {
      throw new Exception(__('You must provide a latitude'));
    }
    if (empty($site['longitude'])) {
      throw new Exception(__('You must provide a longitude'));
    }
    $cur->blog_id = (string) $this->blog->id;
    $cur->theme = (int) $site['theme'];
    if (!empty($site['subtheme'])) {
      $cur->subtheme = (int) $site['subtheme'];
    } else {
      $cur->subtheme = null;
    }
    $cur->subtheme = !empty($site['subtheme'])?(int) $site['subtheme']:null;
    if (!empty($site['name'])) {
      $cur->name = (string) $site['name'];
    }

    $cur->url = $this->getSiteDirectoryURL($site['url'], $site['name'], $site['id']);
    if (!empty($site['description'])) {
      $cur->description = (string) $site['description'];
    }
    if (!empty($site['long_description'])) {
      $cur->long_description = (string) $site['long_description'];
    }
    if (!empty($site['address'])) {
      $cur->address = (string) $site['address'];
    }
    if (!empty($site['direction'])) {
      $cur->direction = (string) $site['direction'];
    }
    if (!empty($site['town'])) {
      $cur->town = (string) $site['town'];
    }
    if (!empty($site['latitude'])) {
      $cur->latitude = (string) $site['latitude'];
    }
    if (!empty($site['longitude'])) {
      $cur->longitude = (string) $site['longitude'];
    }
    if (!empty($site['type'])) {
      $cur->type = (int) $site['type'];
    }    

    if (!empty($site['telephone'])) {
      $cur->telephone = (string) $site['telephone'];
    } else {
      $cur->telephone = null;
    }
    if (!empty($site['email'])) {
      $cur->email = (string) $site['email'];
    } else {
      $cur->email = null;
    }
    if (!empty($site['media_id'])) {
      $cur->media_id = (int) $site['media_id'];
    }
    if (!empty($site['paid'])) {
      $cur->paid = (int) $site['paid'];
    } else {
      $cur->paid = null;
    }
    if (!empty($site['mobile_diffusion'])) {
      $cur->mobile_diffusion = (int) $site['mobile_diffusion'];
    } else {
      $cur->mobile_diffusion = null;
    }
    if (!empty($site['website'])) {
      $cur->website = (string) $site['website'];
    } else {
      $cur->website = null;
    }

    $cur->update('WHERE id = '.(int) $site['id']." AND blog_id = '".$this->con->escape($this->blog->id)."'");
    $this->blog->triggerBlog();
  }

  public function getSiteByName($url) {
    $strReq =  'SELECT s.id, s.theme, s.subtheme, t1.label as theme_label, t2.label as subtheme_label,';
    $strReq .= 't1.url as theme_url, s.name, s.url, s.description, s.long_description,';
    $strReq .= 's.address, s.email, s.telephone, tt.label as type,';
    $strReq .= 's.direction, s.town, s.latitude, s.longitude,';
    $strReq .= 's.paid, s.mobile_diffusion, s.website, s.media_id';
    $strReq .= ' FROM '.$this->table_site.' AS s';
    $strReq .= ' LEFT JOIN '.$this->table_theme.' AS t1 ON s.theme=t1.id';
    $strReq .= ' LEFT JOIN '.$this->table_theme.' AS t2 ON s.subtheme=t2.id';
    $strReq .= ' LEFT JOIN '.$this->table_type.' AS tt ON s.type=tt.id';
    $strReq .= ' WHERE s.blog_id = \''.$this->con->escape($this->blog->id).'\'';
    $strReq .= ' AND status = 1';
    $strReq .= ' AND s.url=\''.$this->con->escape($url).'\'';

    $rs = $this->con->select($strReq);
    $rs = $rs->toStatic();
		
    $this->setMedias($rs);

    return $rs;
  }

  public function getSite($id) {
    $strReq =  'SELECT id, theme, subtheme, name, url, description, long_description,';
    $strReq .=  'address, email, telephone, type,';
    $strReq .=  'direction, town, latitude, longitude,';
    $strReq .=  'paid, mobile_diffusion, website, media_id';
    $strReq .= ' FROM '.$this->table_site;
    $strReq .= ' WHERE blog_id = \''.$this->con->escape($this->blog->id).'\'';
    $strReq .= ' AND id='.$this->con->escape($id);

    $rs = $this->con->select($strReq);
    $rs = $rs->toStatic();
		
    return $rs;
  }

  public function updateStatus($site_ids, $status) {
    if (!isset(self::$Status_action[$status])) {
      throw new Exception(__('That status doesn\'t exists.'.$status));      
    }
    $cur = $this->con->openCursor($this->table_site);

    $cur->status = self::$Status_action[$status];
    $cur->update(' WHERE id IN ('.implode(',', $site_ids).') '.
                 " AND blog_id = '".$this->con->escape($this->blog->id)."' "
                 );
    $this->blog->triggerBlog();
  }    

  public function removeMedia($site_id) {
    $cur = $this->con->openCursor($this->table_site);

    $cur->media_id = null;
    $cur->update(' WHERE id = '.$site_id.
                 " AND blog_id = '".$this->con->escape($this->blog->id)."' "
                 );
    $this->blog->triggerBlog();

    return true;
  }

  public function updatePositions($positions) {
    $cur = $this->con->openCursor($this->table_site);

    foreach ($positions as $site_id => $position) {
      $cur->position = $position;
      $cur->update(' WHERE id='.$this->con->escape($site_id).
                   " AND blog_id = '".$this->con->escape($this->blog->id)."' "
                   );
    }
    $this->blog->triggerBlog();
  }

  public static function geti18nStatus() {
    $status = self::$Status;
    array_walk($status, array('self', 'translate'));

    return $status;
  }

  public static function geti18nDirections() {
    $directions = self::$Directions;
    array_walk($directions, array('self', 'translate'));

    return $directions;
  }

  public static function getDirection($direction_id) {
    return self::$Directions[$direction_id];
  }

  /*
  **/
  private function getSiteDirectoryURL($url, $name, $id=0) {
    if (!empty($url) && !empty($id)) { // update
      $strReq =  'SELECT id';
      $strReq .= ' FROM '.$this->table_site;
      $strReq .= ' WHERE blog_id = \''.$this->con->escape($this->blog->id).'\'';
      $strReq .= ' AND url = \''.$this->con->escape($url).'\'';
      $strReq .= ' AND id != '.$this->con->escape($id);
      
      $rs = $this->con->select($strReq);      
      if ($rs->isEmpty()) {
        return $url;
      } else {
        $url = '';
      }
    }
    
    if (empty($url)) {
      $strReq =  'SELECT id, url, name';
      $strReq .= ' FROM '.$this->table_site;
      $strReq .= ' WHERE blog_id = \''.$this->con->escape($this->blog->id).'\'';
      $strReq .= ' AND name = \''.$this->con->escape($name).'\'';
      $strReq .= ' ORDER BY url DESC';

      $rs = $this->con->select($strReq);
      if (!$rs->isEmpty()) {
        $rs = $this->con->select($strReq);
        $a = array();
        while ($rs->fetch()) {
          $a[] = $rs->url;
        }
			
        natsort($a);
        $t_url = end($a);

        if (preg_match('/(.*?)-([0-9]+)$/',$t_url, $m)) {
          $i = (integer) $m[2] + 1;
        } else {
          $i = 1;
        }

        $url = sprintf('%s-%s', text::str2URL((string) $name), $i);
      }
    } else {
      
    }

    return $url;
  }

  private static function translate(&$item, $key) {
    $item = __($item);
  }

  private function setMedias($rs) {
    global $core;

    $core->media = new dcMedia($core);
    while ($rs->fetch()) {
      if ($rs->media_id) {
        if (($media_file = $core->media->getFile($rs->media_id)) != null) {
          $rs->set('image_path', 
                   $media_file->file_url
                   );
        } else {
          $rs->set('image_path', null);
        }
      } else {
        $rs->set('image_path', null);
      }
    }

    $rs->moveStart();
  }
}
?>