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

class thematicManager
{
  public function __construct($core) {
    $this->core = $core;
    $this->blog = $core->blog;
    $this->con = $this->blog->con;
    $this->table = $this->blog->prefix.'sd_thematic';
  }

  public function getList() {
    $strReq =  'SELECT t1.id, t1.label, t1.position, t1.classname, t1.parent, t2.label as parent_label';
    $strReq .= ' FROM '.$this->table.' AS t1';
    $strReq .= ' LEFT JOIN '.$this->table.' AS t2 ON t1.parent=t2.id';
    $strReq .= ' WHERE t1.blog_id = \''.$this->con->escape($this->blog->id).'\'';
    $strReq .= ' ORDER BY t1.parent ASC, t1.position ASC';

    $rs = $this->con->select($strReq);
    $rs = $rs->toStatic();

    return $rs;
  }
  
  public function add($thematic) {
    $cur = $this->con->openCursor($this->table);
    if (empty($thematic['label'])) {
      throw new Exception(__('You must provide a label'));
    }

    $cur->blog_id = (string) $this->blog->id;
    $cur->label = (string) $thematic['label'];
    $cur->url = text::str2URL((string) $thematic['label']);

    if (!empty($thematic['description'])) {
      $cur->description = (string) $thematic['description'];
    }
    if (!empty($thematic['position'])) {
      $cur->position = (int) $thematic['position'];
    }
    if (!empty($thematic['classname'])) {
      $cur->classname = (string) $thematic['classname'];
    }
    if (!empty($thematic['parent'])) {
      $cur->parent = (int) $thematic['parent'];
    }
    $strReq = 'SELECT MAX(id) FROM '.$this->table;
    $rs = $this->con->select($strReq);
    $cur->id = (int) $rs->f(0) + 1;
		
    $cur->insert();
    $this->blog->triggerBlog();
  }

  public function update($thematic) {
    $cur = $this->con->openCursor($this->table);
    if (empty($thematic['label'])) {
      throw new Exception(__('You must provide a label'));
    }

    $cur->blog_id = (string) $this->blog->id;
    $cur->label = (string) $thematic['label'];
    if (empty($thematic['url'])) {
      $cur->url = text::str2URL((string) $thematic['label']);
    }

    if (!empty($thematic['description'])) {
      $cur->description = (string) $thematic['description'];
    }
    if (!empty($thematic['position'])) {
      $cur->position = (int) $thematic['position'];
    }
    if (!empty($thematic['classname'])) {
      $cur->classname = (string) $thematic['classname'];
    }
    if (!empty($thematic['parent'])) {
      $cur->parent = (int) $thematic['parent'];
    }
    $cur->update('WHERE id = '.(int) $thematic['id']." AND blog_id = '".$this->con->escape($this->blog->id)."'");
    $this->blog->triggerBlog();
  }

  public function updatePositions($positions) {
    $cur = $this->con->openCursor($this->table);

    foreach ($positions as $thematic_id => $position) {
      $cur->position = $position;
      $cur->update(' WHERE id='.$this->con->escape($thematic_id).
		 " AND blog_id = '".$this->con->escape($this->blog->id)."' "
		 );
    }
    $this->blog->triggerBlog();
  }

  public function delete(array $ids=array()) {
    if (empty($ids)) {
      return false;
    }

    $cur = $this->con->openCursor($this->table);
    $strReq = 'DELETE FROM '.$this->table;
    if (count($ids)==1) {
      $strReq .= ' WHERE id = '.$ids[0];
    } else {
      $strReq .= ' WHERE id IN ('.implode(',', $ids).')';
    }
    $this->con->execute($strReq);
  }

  public function getThematic($id) {
    $strReq =  'SELECT label, description, url, position, parent, classname';
    $strReq .= ' FROM '.$this->table;
    $strReq .= ' WHERE blog_id = \''.$this->con->escape($this->blog->id).'\'';
    $strReq .= ' AND id='.$this->con->escape($id);

    $rs = $this->con->select($strReq);
    $rs = $rs->toStatic();
		
    return $rs;
  }

  public function getThematicByURL($url) {
    $strReq =  'SELECT label, url';
    $strReq .= ' FROM '.$this->table;
    $strReq .= ' WHERE blog_id = \''.$this->con->escape($this->blog->id).'\'';
    $strReq .= ' AND url = \''.$this->con->escape($url).'\'';

    $rs = $this->con->select($strReq);
    $rs = $rs->toStatic();
		
    return $rs;    
  }
}