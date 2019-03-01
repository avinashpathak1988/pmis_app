<section class="content">
	<div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Edit Menu</h3>
        </div><!-- /.box-header -->
                          
      	<div class="box-body">
      		<?php echo $this->Form->create('Menu'); ?> 
	        <div class="form-group">
	           <?php echo $this->Form->input('id'); ?>                                        
	           <?php echo $this->Form->input('parent_id',array('type'=>'select','empty'=>'','options'=>$menus,'class'=>'span11 pmis_select')); ?>
	           <?php echo $this->Form->input('name',array('type'=>'text','class'=>'form-control')); ?>
	           <?php echo $this->Form->input('url',array('type'=>'text','class'=>'form-control')); ?>
	           <?php echo $this->Form->input('order',array('type'=>'text','class'=>'form-control')); ?>
	        </div>
	        <div class="checkbox">
	          <label>
	            <?php echo $this->Form->input('is_enable',array('checked'=>true)); ?>
	          </label>
	        </div>
	        <?php echo $this->Form->end(__('Submit')); ?>
      	</div> <!-- /.box-body -->
    </div><!-- /.box -->
</section>