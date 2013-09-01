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

class typeManager
{
  public function __construct($core) {
    $this->core = $core;
    $this->blog = $core->blog;
    $this->con = $this->blog->con;
    $this->table = $this->blog->prefix.'sd_type';
  }

  public function getType($id) {
    $strReq =  'SELECT label';
    $strReq .= ' FROM '.$this->table;
    $strReq .= ' WHERE blog_id = \''.$this->con->escape($this->blog->id).'\'';
    $strReq .= ' AND id='.$this->con->escape($id);

    $rs = $this->con->select($strReq);
    $rs = $rs->toStatic();

    return $rs;
  }

  public function getList() {
    $strReq =  'SELECT id, label';
    $strReq .= ' FROM '.$this->table;
    $strReq .= ' WHERE blog_id = \''.$this->con->escape($this->blog->id).'\'';

    $rs = $this->con->select($strReq);
    $rs = $rs->toStatic();

    return $rs;
  }

  public function add($type) {
    $cur = $this->con->openCursor($this->table);
    if (empty($type['label'])) {
      throw new Exception(__('You must provide a label'));
    }

    $cur->blog_id = (string) $this->blog->id;
    $cur->label = (string) $type['label'];

    $strReq = 'SELECT MAX(id) FROM '.$this->table;
    $rs = $this->con->select($strReq);
    $cur->id = (int) $rs->f(0) + 1;

    $cur->insert();
    $this->blog->triggerBlog();
  }

  public function update($type) {
    $cur = $this->con->openCursor($this->table);
    if (empty($type['label'])) {
      throw new Exception(__('You must provide a label'));
    }

    $cur->blog_id = (string) $this->blog->id;
    $cur->label = (string) $type['label'];

    $cur->update('WHERE id = '.(int) $type['id']." AND blog_id = '".$this->con->escape($this->blog->id)."'");
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
}
