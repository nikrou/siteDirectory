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

class adminSiteDirectoryList extends adminGenericList
{
  public function display($site_directory, $nb_per_page, $enclose_block='') {
    if ($this->rs->isEmpty()) {
      echo '<p><strong>'.__('No site').'</strong></p>';
    } else {
      $pager = new pager($site_directory, $this->rs_count, $nb_per_page, 10);
      $pager->html_prev = $this->html_prev;
      $pager->html_next = $this->html_next;
      $pager->var_site = 'site';
      
      $html_block =
	'<table id="site-directory-list" class="clear" width="100%">'.
	'<thead><tr>'.
	'<th colspan="2">'.__('Name').'</th>'.
	'<th>'.__('Theme').'</th>'.
	'<th>'.__('Sub-theme').'</th>'.
	'<th>'.__('Type').'</th>'.
	'<th>'.__('Direction').'</th>'.
	'<th>'.__('Town').'</th>'.
	'<th>'.__('Paid?').'</th>'.
	'<th>'.__('Status').'</th>'.
	'</tr></thead>'.
	'<tbody>%s</tbody></table>';
      
      if ($enclose_block) {
	$html_block = sprintf($enclose_block, $html_block);
      }
      
      $blocks = explode('%s',$html_block);
      
      echo $blocks[0];
      
      while ($this->rs->fetch()) {
	echo $this->postLine();
      }
      
      echo $blocks[1];
    }
  }
  
  private function postLine() {
    $img = '<img alt="%1$s" title="%1$s" src="images/%2$s" />';
    switch ($this->rs->status) {
    case 1:
      $img_status = sprintf($img, __('published'), 'check-on.png');
      break;
    case 0:
      $img_status = sprintf($img, __('unpublished'), 'check-off.png');
      break;
    case -2:
      $img_status = sprintf($img, __('pending'), 'check-wrn.png');
      break;
    case -99:
      $img_status = sprintf($img, __('deleted'), 'trash.png');
      break;
    }
    
    $res = '<tr class="draggable line'.($this->rs->status != 1 ? ' offline' : '').'"'.
      ' id="p'.$this->rs->id.'">';
    
    $res .=
      '<td class="first">'.
      form::hidden(array('site_position['.$this->rs->id.']'), $this->rs->position).
      form::checkbox(array('sites[]'), $this->rs->id,'','','').'</td>'.
      '<td class="nowrap"><a href="'.$GLOBALS['p_url'].'&amp;action=edit&id='.$this->rs->id.'">'.
      html::escapeHTML($this->rs->name).'</a></td>'.      
      '<td class="nowrap">'.html::escapeHTML($this->rs->theme_label).'</td>'.
      '<td class="nowrap">'.html::escapeHTML($this->rs->subtheme_label).'</td>'.
      '<td class="nowrap">'.$this->rs->type.'</td>'.
      '<td class="nowrap">'.__(siteDirectoryManager::getDirection($this->rs->direction)).'</td>'.
      '<td class="nowrap">'.html::escapeHTML($this->rs->town).'</td>';
    $res .= '<td class="nowrap">';
    if ($this->rs->paid) {
      $res .= __('Yes');
    } else {
      $res .= __('No');
    }
    $res .= '</td>';
    $res .= '<td class="nowrap status">'.$img_status.'</td></tr>';
    
    return $res;
  }
}
?>