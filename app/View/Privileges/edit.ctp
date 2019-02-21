<section class="content">
	<div class="box box-primary">
        <div class="box-header with-border">
        	<div class="actions" align="right">
        <?php echo $this->Html->link(__('Privilege List'), array('action' => 'index'),array('class' => 'btn btn-primary')); ?>
    </div>
          <h3 class="box-title">Add New Privilges</h3>
        </div><!-- /.box-header -->
                          
      	<div class="box-body">
      		<?php echo $this->Form->create('Privilege'); ?>  
	        <div class="form-group">  
	           <?php echo $this->Form->input('id'); ?> 
               <?php echo $this->Form->input('stage_id'); ?>  
               <?php echo $this->Form->input('privilege_right_id'); ?>  
               <?php echo $this->Form->input('prison_id'); ?>  
               <?php echo $this->Form->input('interval_week'); ?> 
	           <?php echo $this->Form->input('duration_min',array('class'=>'form-control validate','autocomplete' => 'off')); ?>
	        </div>
	        <div class="checkbox">
	          <label>
	            <?php echo $this->Form->input('is_enable'); ?>
	          </label>
	        </div>
        	<?php echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true,'onclick'=>"javascript:return validateForm();"))?>
      	</div> <!-- /.box-body -->
    </div><!-- /.box -->
</section>
<script type="text/javascript">
function validateForm(){
    var errcount = 0;
    $('.validate').each(function(){
        if($(this).val() == ''){
            errcount++;
            $(this).addClass('error-text');
            $(this).removeClass('success-text'); 
        }else{
            $(this).removeClass('error-text');
            $(this).addClass('success-text'); 
        }        
    });        
    if(errcount == 0){            
        if(confirm('Are you sure want to save?')){  
            return true;            
        }else{               
            return false;           
        }        
    }else{   
        return false;
    }  
}
</script>
