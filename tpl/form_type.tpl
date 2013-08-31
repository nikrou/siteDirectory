<html>
  <head>
    <title><?php echo $page_title.' - '.__('Types'); ?></title>
    <link rel="stylesheet" type="text/css" media="screen" href="index.php?pf=siteDirectory/css/admin.css"/>
  </head>
  <body>
    <h2>
      <?php echo html::escapeHTML($core->blog->name); ?> &gt; 
      <a href="<?php echo $p_url;?>"><?php echo __('Sites directory');?></a> &gt; <?php echo $page_title;?>
    </h2>

    <?php if (!empty($message)):?>
    <p class="message"><?php echo $message;?></p>
    <?php endif;?>
    
    <form action="<?php echo $p_url;?>" method="post" id="type-form">
      <p class="field">
	<label class="required" for="type_label">
	  <abbr title="<?php echo __('Required field');?>">*</abbr>
	  <?php echo __('Label:');?>
	</label>
	<?php echo form::field('type_label', 100, 255, html::escapeHTML($type['label']), '');?>
      </p>
      <p>
	<?php echo form::hidden('p', 'siteDirectory');?>
	<?php echo form::hidden('object', 'type');?>
	<?php echo form::hidden('action', $action);?>
	<?php echo $core->formNonce();?>
	<input type="submit" name="save_type" value="<?php echo __('Save'); ?>"/>
      </p>
    </form>
  </body>
</html>


