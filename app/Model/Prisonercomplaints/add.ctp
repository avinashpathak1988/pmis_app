<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<h5>Add New Record Prisoner Complaints </h5>
					<div style="float:right;padding-top: 7px;">
						<?php echo $this->Html->link(__('Prisoner Complaints Record List'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
						&nbsp;&nbsp;
					</div>
				</div>
				<div class="widget-content nopadding">
					<?php echo $this->Form->create('Prisonercomplaint',array('class'=>'form-horizontal'));?>
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
								<label class="control-label">Prisoner Number <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'onChange'=>'getPrisonerStationInfo(this.value)','class'=>'form-control','type'=>'select','options'=>$prisonerList, 'empty'=>'-- Select Prisoner Number --','required','id'=>'prisoner_no'));?>
								</div>
							</div>
						</div>
						</div>
					   <div class="row-fluid">
					   <div class="span6">
							<div class="control-group">
								<label class="control-label">Prisoner Name :</label>
								<div class="controls">
									<?php echo $this->Form->text('prisoner_name',array('div'=>false,'label'=>false,'placeholder'=>'Prisoner Name','class'=>'form-control','required','readonly','id'=>'prisoner_name'));?>
								</div>
							</div>
						</div>
						<div class="span6">
							<div class="control-group">
								<label class="control-label">Prison Station Id :</label>
								<div class="controls">
									<?php echo $this->Form->text('prison_station_code',array('div'=>false,'label'=>false,'placeholder'=>'Prison Station Code','class'=>'form-control','required','readonly','id'=>'prison_station_code'));?>
								</div>
							</div>
						</div>
						
						</div>
						<div class="row-fluid">
						<div class="span6">
							<div class="control-group">
								<label class="control-label">Name Of Station <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php
									 echo $this->Form->text('name_of_station',array('div'=>false,'label'=>false,'placeholder'=>'Name Of Station','type'=>'text','class'=>'form-control','required','readonly','id'=>'name_of_station'));?>
								</div>
							</div>
						</div>
						<div class="span6">
							<div class="control-group">
								<label class="control-label">Priority <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->input('priority',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$priorityList, 'empty'=>'-- Select Priority --','required','id'=>'priority'));?>
								</div>
							</div>
						</div>
						
						
						 
						</div>
						
						<div class="row-fluid">
						<div class="span6">
								<div class="control-group">
									<label class="control-label">Date Of Response :</label>
									<div class="controls">
									   <?php
											echo $this->Form->input('date_of_response',array(
											  'div'=>false,
											  'label'=>false,
											  'type'=>'text',
											  'class'=>'datepicker',
											  'data-date-format'=>"dd-mm-yyyy",
											  'readonly'=>'readonly',
											  'required'=>false,
											));
										 ?>
									</div>
								</div>
							</div>
						<div class="span6">
							<div class="control-group">
								<label class="control-label">Respond by <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->input('respond_by',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$userList, 'empty'=>'-- Select Respond by --','required','id'=>'respond_by'));?>
								</div>
							</div>
						</div>
						
						</div>
						

						<div class="row-fluid">
						<div class="span6">
								<div class="control-group">
									<label class="control-label">Remark :</label>
									<div class="controls">
										<?php echo $this->Form->textarea('response',array('div'=>false,'label'=>false,'placeholder'=>'Enter Response','class'=>'form-control','required'));?>
									</div>
								</div>
							</div>
						
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Complaint <?php echo MANDATORY; ?> :</label>
									<div class="controls">
										<?php echo $this->Form->textarea('complaint',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Complaint','class'=>'form-control','required'));?>
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

	if($('#prisoner_no').val() != '')
	{
		getPrisonerStationInfo($('#prisoner_no').val());
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
function getPrisonerStationInfo(id) 
{ 
    $('#prison_station_code').val('');
    $('#name_of_station').val('');
    if(id != '')
    {
        var strURL = '<?php echo $this->Html->url(array('controller'=>'app','action'=>'getPrisonerStationInfo'));?>';
    
        $.post(strURL,{"prisoner_id":id},function(data){  
            
            if(data) { 

                var obj = jQuery.parseJSON(data);
                $('#prison_station_code').val(obj.prison_station_code);
                $('#prisoner_name').val(obj.prisoner_name); 
                $('#name_of_station').val(obj.name_of_station);
            }
        });
    }
}
</script>
