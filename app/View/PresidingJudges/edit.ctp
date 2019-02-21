<section class="content">
	<div class="box box-primary">
        <div class="box-header with-border">
        	<div class="actions" align="right">
        <?php echo $this->Html->link(__('District List'), array('action' => 'index'),array('class' => 'btn btn-primary')); ?>
    </div>
          <h3 class="box-title">Add New District</h3>
        </div><!-- /.box-header -->
                          
      	<div class="box-body">
      		<?php echo $this->Form->create('District'); ?>  
	        <div class="form-group">  
	           <?php echo $this->Form->input('id'); ?>  
	           <?php echo $this->Form->input('name',array('class'=>'form-control validate','autocomplete' => 'off')); ?>
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
