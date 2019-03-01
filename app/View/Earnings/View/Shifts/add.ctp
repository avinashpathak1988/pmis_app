<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<h5>
					<?php
					if(isset($this->request->data['Shift']['id']) && ($this->request->data['Shift']['id'] != 0))
					{
						echo 'Edit Shift';
					}
					else 
					{
						echo 'Add Shift';
					}
					?>
					</h5>
					<div style="float:right;padding-top: 7px;">
						<?php echo $this->Html->link(__('Shift List'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
						&nbsp;&nbsp;
					</div>
				</div>
				<div class="widget-content nopadding">
					<?php echo $this->Form->create('Shift',array('class'=>'form-horizontal'));?>
					<?php echo $this->Form->input('id', array('type'=>'hidden'))?>
						<div class="row-fluid">
							  
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Shift Name<?php echo MANDATORY; ?>:</label>
									<div class="controls">
										<?php echo $this->Form->input('name',array('div'=>false,'label'=>false,'placeholder'=>'Enter Shift Name','class'=>'form-control','required'));?>

									</div>
								</div>
							</div>
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Start Time<?php echo MANDATORY; ?>:</label>
									<div class="controls">
										<?php echo $this->Form->input('start_time',array('type'=>'text','div'=>false,'label'=>false,'placeholder'=>'Enter end time','class'=>'form-control timepicker1','required','readonly'));?>

									</div>
								</div>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span6">
								<div class="control-group">
									<label class="control-label">End Time<?php echo MANDATORY; ?>:</label>
									<div class="controls">
										<?php echo $this->Form->input('end_time',array('type'=>'text','div'=>false,'label'=>false,'placeholder'=>'Enter start time','class'=>'form-control timepicker1','required','readonly'));?>

									</div>
								</div>
							</div>
							<div class="span6">
                            <div class="control-group">
                                <label class="control-label">Is Enable?<?php echo $req; ?> :</label>
                                <div class="controls">
                                    <?php 
                                    if(isset($this->request->data['Shift']['is_enable']) && ($this->request->data['Shift']['is_enable'] == 0))
                                    {
                                    	echo $this->Form->input('is_enable', array('checked'=>false,'div'=>false,'label'=>false)); 
                                    }else
                                    {
                                    	echo $this->Form->input('is_enable', array('checked'=>true,'div'=>false,'label'=>false)); 
                                    }
                                    ?>
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
$(document).ready(function(){
	$('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
	$('.datetimepicker').datetimepicker({format: 'yyyy-mm-dd hh:ii:ss',today:'true',endDate:today});
});
$(function(){

	$("#ShiftAddForm").validate({

	});

});

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
