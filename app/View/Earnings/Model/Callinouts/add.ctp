<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<h5>Add New Record Calls In/out </h5>
					<div style="float:right;padding-top: 7px;">
						<?php echo $this->Html->link(__('Calls in/out Record List'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
						&nbsp;&nbsp;
					</div>
				</div>
				<div class="widget-content nopadding">
					<?php echo $this->Form->create('Callinout',array('class'=>'form-horizontal'));?>
					<?php echo $this->Form->input('id', array('type'=>'hidden'))?>
						<div class="row-fluid">
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Date<?php echo MANDATORY; ?> :</label>
									<div class="controls">
									   <?php
								echo $this->Form->input('call_date',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'text',
								  'class'=>'datepicker',
								  'data-date-format'=>"dd-mm-yyyy",
								  'readonly'=>'readonly',
								  'required',
								));
							 ?>
									</div>
								</div>
							</div>     
							<div class="span6">
							<div class="control-group">
								<label class="control-label">Prisoner Number <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$prisonerList, 'empty'=>'-- Select Prisoner Number --','required','id'=>'prisoner_no'));?>
								</div>
							</div>
						</div>
						</div>
					   <div class="row-fluid">
						<div class="span6">
							<div class="control-group">
								<label class="control-label">From <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->textarea('from',array('div'=>false,'label'=>false,'placeholder'=>'Enter From ','class'=>'form-control','required'));?>
								</div>
							</div>
						</div>
						<div class="span6">
							<div class="control-group">
								<label class="control-label">To <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php
									 echo $this->Form->textarea('to',array('div'=>false,'label'=>false,'placeholder'=>'Enter To','type'=>'text','class'=>'form-control','required'));?>
								</div>
							</div>
						</div>
						</div>
						<div class="row-fluid">
						<div class="span6">
							<div class="control-group">
								<label class="control-label">Personal number :</label>
								<div class="controls">
									<?php echo $this->Form->input('phone_no',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Personal Number','class'=>'form-control','required'=>false,'id'=>'phone_no','readonly'=>'readonly','value'=>$funcall->getName($this->Session->read('Auth.User.prison_id'),"Prison","phone")));?>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Handled By<?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->input('delivered_by',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Handled By','class'=>'form-control','required'));?>
								</div>
							</div>
						</div>
						 <div class="span6">
							<div class="control-group">
								<label class="control-label">Duration:</label>
								<div class="controls">
									<?php echo $this->Form->input('duration',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Duration','class'=>'form-control','required'=>false));?>
								</div>
							</div>
						</div>
					</div>
						
						
					<div class="form-actions" align="center">
						<?php echo $this->Form->input('Submit', array('type'=>'submit', 'class'=>'btn btn-success','div'=>false,'label'=>false,'id'=>'submit','formnovalidate'=>true,'onclick'=>"javascript:return validateForm();"))?>
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
