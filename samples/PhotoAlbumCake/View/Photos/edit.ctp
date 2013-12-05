<?php
    # Include jQuery
    echo $this->Html->script('//code.jquery.com/jquery-1.10.1.min.js');
    # Include cloudinary_js dependencies (requires jQuery)
    echo $this->Form->cloudinary_includes();
    # Setup cloudinary_js using the current cloudinary_php configuration
    echo cloudinary_js_config();
?>
<div class="photos form">
	<fieldset>
<?php echo $this->Form->create('Photo', array('type' => 'file')); ?>
		<legend><?php echo __('Edit Photo'); ?></legend>
	<?php
        echo $this->Form->input('id');

        # Backend upload:
        echo $this->Form->input('cloudinaryIdentifier');
        # Direct upload:
        #echo $this->Form->input('cloudinaryIdentifier', array("type" => "direct_upload"));

        echo $this->Form->input('moderated');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Photo.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Photo.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Photos'), array('action' => 'index')); ?></li>
	</ul>
</div>
