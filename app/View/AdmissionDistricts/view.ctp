<div class="AdmissionDistricts view">
<h2><?php echo __('AdmissionDistrict'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($AdmissionDistrict['AdmissionDistrict']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($AdmissionDistrict['AdmissionDistrict']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Is Enable'); ?></dt>
		<dd>
			<?php echo h($AdmissionDistrict['AdmissionDistrict']['is_enable']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($AdmissionDistrict['AdmissionDistrict']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($AdmissionDistrict['AdmissionDistrict']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit AdmissionDistrict'), array('action' => 'edit', $AdmissionDistrict['AdmissionDistrict']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete AdmissionDistrict'), array('action' => 'delete', $AdmissionDistrict['AdmissionDistrict']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $AdmissionDistrict['AdmissionDistrict']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List AdmissionDistricts'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New AdmissionDistrict'), array('action' => 'add')); ?> </li>
	</ul>
</div>
