<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<h5>Add New Food Report</h5>
					<div style="float:right;padding-top: 7px;">
						<?php echo $this->Html->link(__('Food Report List'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
						&nbsp;&nbsp;
					</div>
				</div>
				<div class="widget-content nopadding">
					<?php echo $this->Form->create('RecordFood',array('class'=>'form-horizontal'));?>
					<?php echo $this->Form->input('id', array('type'=>'hidden'))?>
						<div class="row-fluid">
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Date<?php echo MANDATORY; ?> :</label>
									<div class="controls">
									   <?php
								echo $this->Form->input('date',array(
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
									<label class="control-label">Name Of Station <?php echo MANDATORY; ?> :</label>
									<div class="controls">
										<?php echo $this->Form->input('prison_station_name',array('div'=>false,'label'=>false,'onChange'=>'getPrisonInfo(this.value)','class'=>'form-control','type'=>'select','options'=>$prisonList, 'empty'=>'-- Select Prisoner Number --','required','id'=>'prison_station_name'));?>
									</div>
								</div>
							</div>
						</div>
					   <div class="row-fluid">
						<div class="span6">
							<div class="control-group">
								<label class="control-label">Prison Station Id<?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->input('prison_code',array('div'=>false,'label'=>false,'placeholder'=>'Enter Prison Code','class'=>'form-control','required', 'id'=>'prison_code','readonly'));?>
								</div>
							</div>
						</div>
						<div class="span6">
							<div class="control-group">
								<label class="control-label">Report Type :</label>
								<div class="controls">
									<?php 
									$reportTypes = array('Before Cooking'=>'Before Cooking','After Cooking'=>'After Cooking');
									echo $this->Form->input('report_type',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$reportTypes, 'empty'=>'-- Select Report Type --','required','id'=>'report_type'));?>
								</div>
							</div>
						</div>
						</div>
						
						
						<div class="row-fluid">
						
						 <div class="span6">
							<div class="control-group">
								<label class="control-label">Comment On Food<?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->textarea('comment_on_food',array('div'=>false,'label'=>false,'placeholder'=>'Enter Comment On Food ','class'=>'form-control','required'));?>
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
	if($('#prison_station_name').val() != '')
	{
		getPrisonerStationInfo($('#prison_station_name').val());
	}
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
function getPrisonInfo(id) 
{ 
    $('#prison_code').val('');
    if(id != '')
    {
        var strURL = '<?php echo $this->Html->url(array('controller'=>'app','action'=>'getPrisonInfo'));?>';
    
        $.post(strURL,{"prison_id":id},function(data){  
            
            if(data) { 

                var obj = jQuery.parseJSON(data);
                $('#prison_code').val(obj.prison_code); 
            }
        });
    }
}
</script>
