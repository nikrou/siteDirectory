<html>
  <head>
    <title><?php echo $page_title.' - '.__('Site'); ?></title>
    <link rel="stylesheet" type="text/css" media="screen" href="index.php?pf=siteDirectory/css/admin.css"/>
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript"><?php echo $site_directory_subtheme_js;?></script>
    <?php echo dcPage::jsLoad('index.php?pf=siteDirectory/js/admin.js');?>
  </head>
  <body>
    <h2>
      <?php echo html::escapeHTML($core->blog->name); ?> &gt; 
      <a href="<?php echo $p_url;?>"><?php echo __('Sites directory');?></a> &gt; <?php echo $page_title;?>
    </h2>

    <?php if (!empty($message)):?>
    <p class="message"><?php echo $message;?></p>
    <?php endif;?>
    
    <div class="multi-part" title="<?php echo __('Edit site');?>" id="edit-site">
      <form action="<?php echo $p_url_action;?>" method="post" id="site-form" enctype="multipart/form-data">
	<p class="field">
	  <label class="required" for="site_directory_name">
	    <abbr title="<?php echo __('Required field');?>">*</abbr>
	    <?php echo __('Name:');?>
	  </label>
	  <?php echo form::field('site_directory_name', 100, 255, html::escapeHTML($site['name']), '');?>
	</p>
	<p class="field">
	  <label class="required" for="site_directory_theme">
	    <abbr title="<?php echo __('Required field');?>">*</abbr>
	    <?php echo __('Theme:');?>
	  </label>
	  <?php echo form::combo('site_directory_theme', $combo_theme, $site['theme'], '');?>
	</p>
	<p class="field">
	  <label>
	    <?php echo __('Sub-theme:');?>
	    <?php echo form::combo('site_directory_subtheme', $combo_subtheme, $site['subtheme'], '');?>
	  </label>
	</p>
	<p class="field">
	  <label class="required" for="site_directory_description">
	    <abbr title="<?php echo __('Required field');?>">*</abbr>
	    <?php echo __('Description:');?>
	  </label>
	  <?php echo form::textarea('site_directory_description', 70, 8, html::escapeHTML($site['description']), '');?>
	</p>
	<p class="field">
	  <label class="required" for="site_directory_long_description">
	    <abbr title="<?php echo __('Required field');?>">*</abbr>
	    <?php echo __('Long description:');?>
	  </label>
	  <?php echo form::textarea('site_directory_long_description', 70, 8, html::escapeHTML($site['long_description']), '');?>
	</p>
	<p class="field">
	  <label class="required" for="site_directory_address">
	    <abbr title="<?php echo __('Required field');?>">*</abbr>
	    <?php echo __('Address:');?>
	  </label>
	  <?php echo form::field('site_directory_address', 100, 255, html::escapeHTML($site['address']), '');?>
	</p>
	<p><a id="findLatLng" href=""><?php echo __('Find coordinates on googleMap');?></a></p>

	<p class="field">
	  <label class="required" for="site_directory_town">
	    <abbr title="<?php echo __('Required field');?>">*</abbr>
	    <?php echo __('Town:');?>
	  </label>
	  <?php echo form::field('site_directory_town', 100, 255, html::escapeHTML($site['town']), '');?>
	</p>
	<p class="field">
	  <label class="required" for="site_directory_latitude">
	    <abbr title="<?php echo __('Required field');?>">*</abbr>
	    <?php echo __('Latitude:');?>
	  </label>
	  <?php echo form::field('site_directory_latitude', 50, 255, html::escapeHTML($site['latitude']), '');?>
	</p>
	<p class="field">
	  <label class="required" for="site_directory_longitude">
	    <abbr title="<?php echo __('Required field');?>">*</abbr>
	    <?php echo __('Longitude:');?>
	  </label>
	  <?php echo form::field('site_directory_longitude', 50, 255, html::escapeHTML($site['longitude']), '');?>
	</p>
	<p class="field">
	  <label>
	    <?php echo __('Telephone:').' '.form::field('site_directory_telephone', 50, 255, html::escapeHTML($site['telephone']), '');?>
	  </label>
	</p>
	<p class="field">
	  <label>
	    <?php echo __('Email:').' '.form::field('site_directory_email', 50, 255, html::escapeHTML($site['email']), '');?>
	  </label>
	</p>
	<p class="field">
	  <label class="required" for="site_directory_type">
	    <abbr title="<?php echo __('Required field');?>">*</abbr>
	    <?php echo __('Type:');?>
	  </label>
	  <?php echo form::combo('site_directory_type', $combo_type, $site['type'], '');?>
	</p>
	<div class="field">
	  <label>
	    <?php echo __('Image:');?>
	  </label>
	  <?php if (!empty($site['media_id'])):?>
	  <?php echo form::hidden('site_directory_media_id', $site['media_id']);?>
	  <div class="image" id="site-directory-media">
	      <a href="<?php echo $site['media_file']['link'];?>">
		<img src="<?php echo $site['media_file']['media_icon'];?>" alt="" />
	      </a>
	      <?php echo $site['media_file']['media_name'];?>
	      <a class="media-remove" title="<?php echo __('Remove image from that site?');?>" href="<?php echo $site['media_file']['delete_url'];?>">
		<img src="images/trash.png" alt="<?php echo __('delete');?>"/>
	      </a>
	  </div>
	  <?php endif;?>
	  <input id="site-directory-image" class="image-file<?php if (!empty($site['media_id'])):?> hidden<?php endif;?>" type="file" name="site_directory_image"/>
	</div>
	<p class="field">
	  <label class="required" for="site_directory_direction">
	    <abbr title="<?php echo __('Required field');?>">*</abbr>
	    <?php echo __('Direction:');?>
	  </label>
	  <?php echo form::combo('site_directory_direction', $combo_direction, $site['direction'], '');?>
	</p>
	<p class="field">
	  <label>
	    <?php echo __('Paid?').form::checkbox('site_directory_paid', 1, $site['paid'], '', 15);?>
	  </label>
	</p>
	<p class="field">
	  <label>
	    <?php echo __('Mobile diffusion?').form::checkbox('site_directory_mobile_diffusion', 1, $site['mobile_diffusion'], '', 15);?>
	  </label>
	</p>
	<p class="field">
	  <label>
	    <?php echo __('Website:').' '.form::field('site_directory_website', 100, 255, html::escapeHTML($site['website']), '');?>
	  </label>
	</p>
	<p>
	  <?php echo form::hidden('MAX_FILE_SIZE', DC_MAX_UPLOAD_SIZE);?>
	  <?php echo form::hidden('p', 'siteDirectory');?>
	  <?php echo form::hidden('action', $action);?>
	  <?php if (!empty($site['id'])):?>
	  <?php echo form::hidden('id', $site['id']);?>
	  <?php endif;?>
	  <?php echo $core->formNonce();?>
	  <input type="submit" name="save_site_directory" value="<?php echo __('Save'); ?>"/>
	</p>
      </form>
    </div>
    <?php dcPage::helpBlock('sitedirectory_site');?>
  </body>
</html>
