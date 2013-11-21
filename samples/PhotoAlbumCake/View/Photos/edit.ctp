<?php
    # Include jQuery
    echo $this->Html->script('//code.jquery.com/jquery-1.10.1.min.js');
    # Include cloudinary_js dependencies (requires jQuery)
    echo $this->Cloudinary->cloudinary_includes();
    # Setup cloudinary_js using the current cloudinary_php configuration
    echo cloudinary_js_config();
?>
<div class="photos form">
<?php echo $this->Cloudinary->create('Photo', array('type' => 'file')); ?>
	<fieldset>
		<legend><?php echo __('Edit Photo'); ?></legend>
	<?php
        echo $this->Cloudinary->input('id');
        # Backend upload:
        echo $this->Cloudinary->input('cloudinaryIdentifier');
        # Direct upload:
        #echo $this->Cloudinary->input('cloudinaryIdentifier', array("type" => "direct_upload"));
        echo $this->Cloudinary->input('moderated');
	?>
	</fieldset>
<?php echo $this->Cloudinary->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Cloudinary->postLink(__('Delete'), array('action' => 'delete', $this->Cloudinary->value('Photo.id')), null, __('Are you sure you want to delete # %s?', $this->Cloudinary->value('Photo.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Photos'), array('action' => 'index')); ?></li>
	</ul>
</div>
