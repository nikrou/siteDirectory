<html>
  <head>
    <title><?php echo $page_title.' - '.__('Thematics'); ?></title>
    <link rel="stylesheet" type="text/css" media="screen" href="index.php?pf=siteDirectory/css/admin.css"/>
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
    
    <form action="<?php echo $p_url;?>" method="post" id="thematic-form">
      <p class="field">
	<label class="required" for="thematic_label">
	  <abbr title="<?php echo __('Required field');?>">*</abbr>
	  <?php echo __('Label:');?>
	</label>
	<?php echo form::field('thematic_label', 100, 255, html::escapeHTML($thematic['label']), '');?>
      </p>
      <p class="field">
	<label>
	  <?php echo __('Description:').' '.form::textarea('thematic_description', 70, 8, html::escapeHTML($thematic['description']), '');?>
	</label>
      </p>
      <p class="field">
	<label>
	  <?php echo __('CSS classname:').' '.form::field('thematic_classname', 10, 25, html::escapeHTML($thematic['classname']), '');?>
	</label>
      </p>
      <p class="field">
	<label>
	  <?php echo __('Position:').' '.form::field('thematic_position', 10, 25, html::escapeHTML($thematic['position']), '');?>
	</label>
      </p>
      <p class="field">
	<label>
	  <?php echo __('Parent:');?>
	  <?php echo form::combo('thematic_parent', $combo_thematics, $thematic['parent'], '');?>
	</label>
      </p>
      <p>
	<?php echo form::hidden('p', 'siteDirectory');?>
	<?php echo form::hidden('object', 'thematic');?>
	<?php echo form::hidden('action', $action);?>
	<?php echo $core->formNonce();?>
	<input type="submit" name="save_thematic" value="<?php echo __('Save'); ?>"/>
      </p>
    </form>
  </body>
</html>

