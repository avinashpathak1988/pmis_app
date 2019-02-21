<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<h5>Add New Record Court Level </h5>
					<div style="float:right;padding-top: 7px;">
						<?php echo $this->Html->link(__('Court Level List'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
						&nbsp;&nbsp;
					</div>
				</div>
				<div class="widget-content nopadding">
					<?php echo $this->Form->create('Courtlevel',array('class'=>'form-horizontal'));?>
					<?php echo $this->Form->input('id', array('type'=>'hidden'))?>
						<div class="row-fluid">
							  
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Court Level Name<?php echo MANDATORY; ?>:</label>
									<div class="controls">
										<?php echo $this->Form->input('name',array('div'=>false,'label'=>false,'placeholder'=>'Enter Court Level Name','class'=>'form-control','required'));?>

									</div>
								</div>
							</div>
						</div>
					   
						
					<div class="form-actions" align="center">
						<?php echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true))?>
					</div>
					<?php echo $this->Form->end();?>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(function(){
    $("#CourtlevelAddForm").validate({
     
      ignore: "",
            rules: {  
                'data[Courtlevel][name]': {
                    required: true,
                },
           },
            messages: {
                'data[Courtlevel][name]': {
                    required: "This Field is Required.",
                },
            },
               
    });
  });
</script>
<script type="text/javascript">
$(document).ready(function(){
	$('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
	$('.datetimepicker').datetimepicker({format: 'yyyy-mm-dd hh:ii:ss',today:'true',endDate:today});
});
</script>
<script>
// function validateForm(){
// 	var errcount = 0;
// 	$('.validate').each(function(){
// 		if($(this).val() == ''){
// 			errcount++;
// 			$(this).addClass('error-text');
// 			$(this).removeClass('success-text'); 
// 		}else{
// 			$(this).removeClass('error-text');
// 			$(this).addClass('success-text'); 
// 		}        
// 	});        
// 	if(errcount == 0){            
// 		if(confirm('Are you sure want to save?')){  
// 			return true;            
// 		}else{               
// 			return false;           
// 		}        
// 	}else{   
// 		return false;
// 	}  
// }
</script>

