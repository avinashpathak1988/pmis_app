<div class="wardcells view">
<h2><?php echo __('WardCell'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($wardcell['WardCell']['id']); ?>
			&nbsp;
		</dd>
		<dd>
			<?php echo h($wardcell['WardCell']['ward_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($wardcell['WardCell']['cell_name']); ?>
			&nbsp;
		</dd>
		<dd>
			<?php echo h($wardcell['WardCell']['cell_no']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Is Enable'); ?></dt>
		<dd>
			<?php echo h($wardcell['WardCell']['is_enable']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($wardcell['WardCell']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($wardcell['WardCell']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit WardCell'), array('action' => 'edit', $wardcell['WardCell']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete WardCell'), array('action' => 'delete', $wardcell['WardCell']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $wardcell['WardCell']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Cells'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cell'), array('action' => 'add')); ?> </li>
	</ul>
</div>
