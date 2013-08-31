<html>
  <head>
    <title>Site Directory</title>
    <link rel="stylesheet" type="text/css" media="screen" href="index.php?pf=siteDirectory/css/admin.css"/>
    <?php echo dcPage::jsLoad('index.php?pf=siteDirectory/js/jquery.tablednd.js');?>
    <?php echo dcPage::jsLoad('index.php?pf=siteDirectory/js/ui.core.js');?>
    <?php echo dcPage::jsLoad('index.php?pf=siteDirectory/js/jquery.order.js');?>
    <?php echo dcPage::jsLoad('index.php?pf=siteDirectory/js/admin.js');?>
    <?php echo dcPage::jsPageTabs($default_tab);?>
  </head>
  <body>
    <h2><?php echo html::escapeHTML($core->blog->name); ?> &gt; Site Directory</h2>
    <?php if (!empty($message)):?>
    <p class="message"><?php echo $message;?></p>
    <?php endif;?>
    <?php if ($is_super_admin):?>
    <div class="multi-part" id="sitedirectory_settings" title="<?php echo __('Settings'); ?>">
      <form action="<?php echo $p_url;?>" method="post" enctype="multipart/form-data">
	<fieldset>
	  <legend><?php echo __('Plugin activation'); ?></legend>
	  <p class="field">
	    <label class="classic">
	      <?php echo form::checkbox('sitedirectory_active', 1, $sitedirectory_active); ?>
	      <?php echo __('Enable Site Directory plugin');?>
	    </label>
	  </p>
	</fieldset>
	<?php if ($sitedirectory_active):?>
	<fieldset>
	  <legend><?php echo __('Advanced options'); ?></legend>
	  <p class="field">
	    <label><?php echo __('Media sub-directory:'),' ';?>
	      <?php echo form::field('sitedirectory_media_subdirectory', 60, 255, $sitedirectory_media_subdirectory); ?>
	    </label>
	  </p>
	  <p class="field">
	    <label><?php echo __('Thematics page prefix:'),' ';?>
	      <?php echo form::field('sitedirectory_thematics_prefix', 60, 255, $sitedirectory_thematics_prefix); ?>
	    </label>
	  </p>
	  <p class="field">
	    <label><?php echo __('Thematic page prefix:'),' ';?>
	      <?php echo form::field('sitedirectory_thematic_prefix', 60, 255, $sitedirectory_thematic_prefix); ?>
	    </label>
	  </p>
	  <p class="field">
	    <label><?php echo __('Site page prefix:'),' ';?>
	      <?php echo form::field('sitedirectory_site_prefix', 60, 255, $sitedirectory_site_prefix); ?>
	    </label>
	  </p>
	</fieldset>
	<fieldset>
	  <legend><?php echo __('Map options'); ?></legend>
	  <p>
	    <label class="classic"><?php echo __('Zoom level:'),' ';?>
	      <?php echo form::field('sitedirectory_map_zoom', 60, 255, $sitedirectory_map_zoom); ?>
	    </label>
	  </p>
	</fieldset>
	<?php endif;?>
	<?php echo form::hidden('p','siteDirectory');?>
	<?php echo $core->formNonce();?>
	<input type="submit" name="saveconfig" value="<?php echo __('Save configuration'); ?>" />
      </form>
    </div>
    <?php endif;?>
    <?php if ($sitedirectory_active):?>
    <div class="multi-part" id="sitedirectory_list" title="<?php echo __('Sites directory'); ?>">
      <p><a class="button" href="<?php echo $p_url;?>&amp;action=add"><?php echo __('New site');?></a></p>
      <?php if (!$core->error->flag()):?>
      <?php
	 $site_block = '<form action="'.$p_url.'" method="post" id="form-sites">';
	 $site_block .= '%s';
	 $site_block .= '<div class="two-cols">';
	 $site_block .= '<p class="col checkboxes-helpers"><input type="submit" class="save-order disabled" disabled="disabled" name="save_order" value="'.__('Save order').'"/></p>';
	 if ($is_super_admin) {
	 $site_block .= '<p class="col right">';
	 $site_block .= __('Selected sites action:').' ';
	 $site_block .= form::combo('action', $combo_action);
	 $site_block .= '<input type="submit" value="'.__('ok').'" /></p>';
	 }
	 $site_block .= $core->formNonce();
	 $site_block .= '</div></form>';
         $sites_list->display($page, 10, $site_block);
      ?>
      <?php endif;?>
    </div>    
    <div class="multi-part" id="types_list" title="<?php echo __('Types'); ?>">
      <p><a class="button" href="<?php echo $p_url;?>&amp;object=type&amp;action=add"><?php echo __('New type');?></a></p>
      <?php if ($types->isEmpty()):?>
      <p><strong><?php echo __('No type');?></strong></p>
      <?php else:?>
      <form action="<?php echo $p_url;?>" method="post">
	<table class="types" id="types-list">
	  <thead>
	    <tr>
	      <th>&nbsp;</th>
	      <th><?php echo __('Label');?></th>
	    </tr>
	  </thead>
	  <tbody>
	    <?php while ($types->fetch()):?>
	    <tr>
	      <td>
		<?php echo form::checkbox(array('types[]'), $types->id, '', '', '');?>
	      </td>
	      <td>
		<a href="<?php echo $p_url.'&amp;object=type&amp;action=edit&amp;id=',$types->id;?>"><?php echo html::escapeHTML(text::cutString($types->label, 50));?></a>
	      </td>
	    </tr>
	    <?php endwhile;?>
	  </tbody>
	</table>
	<p>
	  <?php echo form::hidden('p', 'siteDirectory');?>
	  <?php echo form::hidden('object', 'type');?>
	  <input type="submit" name="do_remove" value="<?php echo __('Remove selected');?>"/>
	  <?php echo $core->formNonce();?>
	</p>
      </form>
      <?php endif;?>
    </div>
    <div class="multi-part" id="thematics_list" title="<?php echo __('Thematics'); ?>">
      <p><a class="button" href="<?php echo $p_url;?>&amp;object=thematic&amp;action=add"><?php echo __('New thematic');?></a></p>
      <?php if (count($thematics)==0):?>
      <p><strong><?php echo __('No thematic');?></strong></p>
      <?php else:?>
      <form action="<?php echo $p_url;?>" method="post">
	<table class="thematics" id="thematics-list">
	  <thead>
	    <tr>
	      <th>&nbsp;</th>
	      <th><?php echo __('Name');?></th>
	      <th><?php echo __('Parent');?></th>
	      <th><?php echo __('Classname');?></th>
	    </tr>
	  </thead>
	  <tbody>
	    <?php foreach ($thematics as $id => $t):?>
	    <tr class="draggable line">
	      <td>
		<input type="hidden" name="thematic_position[<?php echo $id;?>]" value="<?php echo $t['position'];?>"/>
		<?php if (count($t['children'])>0):;?>	      
		<img src="images/menu_on.png" alt=""/>
		<?php else:?>
		<?php echo form::checkbox(array('thematics[]'), $id, '', '', '');?>
		<?php endif;?>
	      </td>
	      <td class="nowrap name">
		<a href="<?php echo $p_url.'&amp;object=thematic&amp;action=edit&amp;id=',$id;?>"><?php echo html::escapeHTML(text::cutString($t['label'], 50));?></a>
	      </td>
	      <td class="parent">
		<?php if (count($t['children'])>0 && !empty($t['parent_label'])):?>
		<?php echo $t['parent_label'];?>
		<?php endif;?>
	      </td>
	      <td class="classname">
		<?php echo $t['classname'];?>
	      </td>
	    </tr>
	    <?php if (count($t['children'])>0):;?>	      
	    <?php foreach ($t['children'] as $t_id => $tt):?>
	    <tr class="draggable line">
	      <td colspan="2" class="nowrap checkbox">
		<input type="hidden" name="thematic_position[<?php echo $t_id;?>]" value="<?php echo $tt['position'];?>"/>
		<?php echo form::checkbox(array('thematics[]'), $t_id, '', '', '');?>
		<a href="<?php echo $p_url.'&amp;object=thematic&amp;action=edit&amp;id=',$t_id;?>"><?php echo html::escapeHTML(text::cutString($tt['label'], 50));?></a>
	      </td>
	      <td class="nowrap parent">
		<?php echo $tt['parent_label'];?>
	      </td>
	      <td>&nbsp;</td>
	    </tr>
	    <?php endforeach;?>
	    <?php endif;?>
	    <?php endforeach;?>
	  </tbody>
	</table>
	<p>
	  <input type="submit" class="save-order disabled" disabled="disabled" name="save_order" value="<?php echo __('Save order');?>"/>
	  <input type="submit" name="do_remove" value="<?php echo __('Remove selected');?>"/>
	  <?php echo form::hidden('p', 'siteDirectory');?>
	  <?php echo form::hidden('object', 'thematic');?>
	  <?php echo $core->formNonce();?>
	</p>
      </form>
      <?php endif;?>
    </div>
    <div class="multi-part" id="sitedirectory_install" title="<?php echo __('Installation');?>">
      <p><?php echo __('The plugin define new tags for template:');?></p>
      <h3><?php echo __('Thematics list');?></h3>
      <ul>
	<li><strong>Thematics</strong> (<?php echo __('block');?>) : <?php echo __('Loop to display thematics list.');?></li>
	<li><strong>ThematicsHeader</strong> (<?php echo __('block');?>) : <?php echo __('First thematics result container.');?></li>
	<li><strong>ThematicsFooter</strong> (<?php echo __('block');?>) : <?php echo __('Last thematics result container.');?></li>
	<li><strong>ThematicLabel</strong> (<?php echo __('value');?>) : <?php echo __('Thematic label');?></li>
	<li><strong>ThematicURL</strong> (<?php echo __('value');?>) : <?php echo __('Link to thematic page');?></li>
      </ul>

      <p><?php printf(__('Code example to add to your theme <strong>%s</strong> template:'), '_top.html');?></p>
      <pre>
	&lt;tpl:Thematics&gt;
	&nbsp;&nbsp;&lt;tpl:ThematicsHeader&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&lt;ul&nbsp;id=&quot;top-annuaire&quot;&gt;
	&nbsp;&nbsp;&lt;/tpl:ThematicsHeader&gt;
	&nbsp;&nbsp;&lt;li&gt;&lt;a&nbsp;class=&quot;{{tpl:ThematicClassname}}&quot;&nbsp;href=&quot;{{tpl:ThematicURL}}&quot;&gt;{{tpl:ThematicLabel}}&lt;/a&gt;&lt;/li&gt;
	&nbsp;&nbsp;&lt;tpl:ThematicsFooter&gt;
	&nbsp;&nbsp;&lt;/ul&gt;
	&nbsp;&nbsp;&lt;/tpl:ThematicsFooter&gt;
	&lt;/tpl:Thematics&gt;
      </pre>
      <p><?php echo __('You can also include the following code in your template to use default layout');?></p>
      <pre>
	{{tpl:include src="inc_thematics.html"}}
      </pre>

      <h3><?php echo __('Thematic page');?></h3>
      <ul>
	<li><strong>ThematicPageLabel</strong> (<?php echo __('value');?>) : <?php echo __('Thematic page title');?></li>	
	<li><strong>SitesDirections</strong> (<?php echo __('block');?>) : <?php echo __('Loop to display sites group by direction.');?></li>
	<li><strong>SiteDirection</strong> (<?php echo __('value');?>) : <?php echo __('Direction label');?></li>
	<li><strong>SitesSubthemes</strong> (<?php echo __('block');?>) : <?php echo __('Loop to display sites group by sub-themes.');?></li>
	<li><strong>SitesSubThemeLabel</strong> (<?php echo __('value');?>) : <?php echo __('Sub-theme label');?></li>
	<li>
	  <strong>Sites</strong> (<?php echo __('block');?>) : <?php echo __('Loop to display sites list.');?><br/>
	  <span class="info-params"><?php echo __('attribute');?> subtheme (1/0) : <?php echo __('To display or not sites with sub-theme');?></span>
	</li>
	<li><strong>SiteURL</strong> (<?php echo __('value');?>) : <?php echo __('Link to a site');?></li>	
	<li><strong>SiteName</strong> (<?php echo __('value');?>) : <?php echo __('Name of a site');?></li>	
      </ul>
      <p><?php echo __('All tags in site page page can be used in thematic page');?>
      <p><?php printf(__('Code example to add to your theme <strong>%s</strong> template:'), 'thematic.html');?></p>
      <pre>
	&lt;div&nbsp;class=&quot;sites-directory&quot;&gt;
	&nbsp;&nbsp;&lt;h1&gt;{{tpl:ThematicPageLabel}}&nbsp;{{tpl:lang&nbsp;in&nbsp;your&nbsp;region}}&lt;/h1&gt;
	&nbsp;&nbsp;&lt;tpl:SitesDirections&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&lt;div&nbsp;class=&quot;site-directory-direction&nbsp;direction-{{tpl:SiteDirectionClassname}}&quot;&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;h2&gt;{{tpl:SiteDirection}}&lt;/h2&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;tpl:SitesSubThemes&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;h3&gt;{{tpl:SitesSubThemeLabel}}&lt;/h3&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;tpl:Sites&nbsp;subtheme=&quot;1&quot;&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;div&nbsp;class=&quot;site-directory-summary&quot;&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;p&nbsp;class=&quot;name&quot;&gt;&lt;a&nbsp;href=&quot;{{tpl:SiteURL}}&quot;&gt;{{tpl:SiteName}}&lt;/a&gt;&lt;/p&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;div&nbsp;class=&quot;description&quot;&gt;{{tpl:SiteDescription}}&lt;/div&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/div&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tpl:Sites&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tpl:SitesSubThemes&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;tpl:Sites&nbsp;subtheme=&quot;0&quot;&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;div&nbsp;class=&quot;site-directory-summary&quot;&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;p&nbsp;class=&quot;name&quot;&gt;&lt;a&nbsp;href=&quot;{{tpl:SiteURL}}&quot;&gt;{{tpl:SiteName}}&lt;/a&gt;&lt;/p&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;div&nbsp;class=&quot;description&quot;&gt;{{tpl:SiteDescription}}&lt;/div&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/div&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/tpl:Sites&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&lt;/div&gt;
	&nbsp;&nbsp;&lt;/tpl:SitesDirections&gt;
	&lt;/div&gt;
      </pre>
      <p><?php echo __('You can also include the following code in your template to use default layout');?></p>
      <pre>
	{{tpl:include src="inc_thematic.html"}}
      </pre>

      <h3><?php echo __('Site page');?></h3>
      <ul>
	<li><strong>SiteName</strong> (<?php echo __('value');?>) : <?php echo __('Name of a site');?></li>	
	<li><strong>SiteType</strong> (<?php echo __('value');?>) : <?php echo __('Type of a site');?></li>	
	<li><strong>SiteThemeLabel</strong> (<?php echo __('value');?>) : <?php echo __('Theme label of a site');?></li>	
	<li><strong>SiteSubThemeLabel</strong> (<?php echo __('value');?>) : <?php echo __('Sub-theme label of a site');?></li>	
	<li><strong>SiteAddress</strong> (<?php echo __('value');?>) : <?php echo __('Address of a site');?></li>	
	<li><strong>SiteTown</strong> (<?php echo __('value');?>) : <?php echo __('Town of a site');?></li>	
	<li><strong>SiteDescription</strong> (<?php echo __('value');?>) : <?php echo __('Description of a site');?></li>	
	<li><strong>SiteLongDescription</strong> (<?php echo __('value');?>) : <?php echo __('Long description of a site');?></li>	
	<li><strong>IfSiteImage</strong> (<?php echo __('block');?>) : <?php echo __('Test if image field exists for that site.');?></li>
	<li><strong>SiteImage</strong> (<?php echo __('value');?>) : <?php echo __('Image of a site');?></li>	
	<li><strong>SiteEmail</strong> (<?php echo __('value');?>) : <?php echo __('Email of a site');?></li>	
	<li><strong>SiteTelephone</strong> (<?php echo __('value');?>) : <?php echo __('Telephone of a site');?></li>	
	<li><strong>SiteWebiste</strong> (<?php echo __('value');?>) : <?php echo __('Website of a site');?></li>	
	<li><strong>SiteDirectionClassname</strong> (<?php echo __('value');?>) : <?php echo __('Direction of a site in lower case for css purpose');?></li>	
	<li><strong>SiteLatitude</strong> (<?php echo __('value');?>) : <?php echo __('Latitude of a site');?></li>
	<li><strong>SiteLongitude</strong> (<?php echo __('value');?>) : <?php echo __('Longitude of a site');?></li>
	<li><strong>IfSitePaid</strong> (<?php echo __('block');?>) : <?php echo __('Test if site has been paid');?></li>
	<li><strong>IfSiteMobileDiffusion</strong> (<?php echo __('block');?>) : <?php echo __('Test if site can be diffused on mobile');?></li>
      </ul>
      <p><?php printf(__('Code example to add to your theme <strong>%s</strong> template:'), 'site.html');?></p>
      <pre>
	&lt;div&nbsp;class=&quot;site-directory&quot;&gt;
	&nbsp;&nbsp;&lt;a&nbsp;class=&quot;right&quot;&nbsp;href=&quot;{{tpl:SiteThemeURL}}&quot;&gt;{{tpl:lang&nbsp;Back&nbsp;to&nbsp;list}}&lt;/a&gt;
	&nbsp;&nbsp;&lt;h1&gt;&lt;a&nbsp;href=&quot;{{tpl:SiteThemeURL}}&quot;&gt;{{tpl:SiteThemeLabel}}&nbsp;{{tpl:lang&nbsp;in&nbsp;your&nbsp;region}}&lt;/a&gt;&lt;/h1&gt;
	&nbsp;&nbsp;&lt;tpl:IfSiteImage&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&lt;div&nbsp;class=&quot;image&quot;&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;img&nbsp;src=&quot;{{tpl:SiteImage}}&quot;&nbsp;alt=&quot;&quot;/&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&lt;/div&gt;
	&nbsp;&nbsp;&lt;/tpl:IfSiteImage&gt;
	&nbsp;&nbsp;&lt;div&nbsp;class=&quot;infos&quot;&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&lt;p&nbsp;class=&quot;name&quot;&gt;{{tpl:SiteName}}&lt;/p&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&lt;p&nbsp;class=&quot;address&quot;&gt;{{tpl:SiteAddress}}&amp;nbsp;-&amp;nbsp;{{tpl:SiteTown}}&lt;/p&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&lt;p&nbsp;class=&quot;type&quot;&gt;{{tpl:SiteType}}&lt;/p&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&lt;p&nbsp;class=&quot;long_description&quot;&gt;{{tpl:SiteLongDescription}}&lt;/p&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&lt;p&nbsp;class=&quot;email&quot;&gt;&lt;a&nbsp;href=&quot;mailto:{{tpl:SiteEmail}}&quot;&gt;{{tpl:SiteEmail}}&lt;/a&gt;&lt;/p&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&lt;p&nbsp;class=&quot;telephone&quot;&gt;{{tpl:SiteTelephone}}&lt;/p&gt;
	&nbsp;&nbsp;&nbsp;&nbsp;&lt;p&nbsp;class=&quot;website&quot;&gt;&lt;a&nbsp;href=&quot;{{tpl:SiteWebsite}}&quot;&gt;{{tpl:SiteWebsite}}&lt;/a&gt;&lt;/p&gt;
	&nbsp;&nbsp;&lt;/div&gt;
	&lt;/div&gt;
      </pre>
      <p><?php echo __('You can also include the following code in your template to use default layout');?></p>
      <pre>
	{{tpl:include src="inc_site.html"}}
      </pre>
    </div>
    <?php endif;?>
    <div class="multi-part" id="sitedirectory_about" title="<?php echo __('About'); ?>">
      <p>
	<?php echo __('If you want more informations on that plugin or have new ideas to develope it, or want to submit a bug or need help (to install or configure it) or for anything else ...');?></p>
      <p>
	<?php printf(__('Go to %sthe dedicated page%s in'), 
	      '<a href="http://www.nikrou.net/pages/sitedirectory">',
	      '</a>');?>
	<a href="http://www.nikrou.net/">Le journal de nikrou</a>
      </p>
      <p><?php echo __('Made by:');?>
	<a href="http://www.nikrou.net/contact">Nicolas</a> (nikrou)
      </p>
    </div>
    <?php dcPage::helpBlock('sitedirectory_list');?>
  </body>
</html>

