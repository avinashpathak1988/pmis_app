<div class="prisonertransferlogins view">
<h2><?php echo __('PrisonerTransferLogin'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($prisonertransferlogin['PrisonerTransferLogin']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Original Station'); ?></dt>
		<dd>
			<?php echo h($prisonertransferlogin['PrisonerTransferLogin']['orginal_station']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Destination Station'); ?></dt>
		<dd>
			<?php echo h($prisonertransferlogin['PrisonerTransferLogin']['destination_station']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date of Transfer Request'); ?></dt>
		<dd>
			<?php echo h($prisonertransferlogin['PrisonerTransferLogin']['date_of_transfer_request']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Convict'); ?></dt>
		<dd>
			<?php echo h($prisonertransferlogin['PrisonerTransferLogin']['convict']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Remand'); ?></dt>
		<dd>
			<?php echo h($prisonertransferlogin['PrisonerTransferLogin']['remand']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Debtor'); ?></dt>
		<dd>
			<?php echo h($prisonertransferlogin['PrisonerTransferLogin']['debtor']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Reason'); ?></dt>
		<dd>
			<?php echo h($prisonertransferlogin['PrisonerTransferLogin']['reason']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Remarks'); ?></dt>
		<dd>
			<?php echo h($prisonertransferlogin['PrisonerTransferLogin']['remarks']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit PrisonerTransferLogin'), array('action' => 'edit', $prisonertransferlogin['PrisonerTransferLogin']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete PrisonerTransferLogin'), array('action' => 'delete', $prisonertransferlogin['PrisonerTransferLogin']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $prisonertransferlogin['PrisonerTransferLogin']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List PrisonerTransferLogin'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New PrisonerTransferLogin'), array('action' => 'add')); ?> </li>
	</ul>
</div>
