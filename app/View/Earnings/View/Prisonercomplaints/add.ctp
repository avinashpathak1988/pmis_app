<?php
if(isset($this->data['Prisonercomplaint']['date_of_response']) && $this->data['Prisonercomplaint']['date_of_response']!=''){
	$this->request->data['Prisonercomplaint']['date_of_response'] = date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($this->data['Prisonercomplaint']['date_of_response']));
}
if(isset($this->data['Prisonercomplaint']['action_date']) && $this->data['Prisonercomplaint']['action_date']!=''){
	$this->request->data['Prisonercomplaint']['action_date'] = date(Configure::read('UGANDA-DATE-FORMAT'), strtotime($this->data['Prisonercomplaint']['action_date']));
}
?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<h5>Add Prisoner Complaints </h5>
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
											  //'class'=>'datepicker',
											  'data-date-format'=>"dd-mm-yyyy",
											  'readonly'=>'readonly',
											  'required',
											  'value'=>date("d-m-Y")
											));
										 ?>
									</div>
								</div>
							</div>
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Time<?php echo MANDATORY; ?> :</label>
									<div class="controls">
									   <?php
											echo $this->Form->input('time',array(
											  'div'=>false,
											  'label'=>false,
											  'type'=>'text',
											  //'class'=>'datepicker',
											  //'data-date-format'=>"dd-mm-yyyy",
											  'readonly'=>'readonly',
											  'required',
											  'value'=>date("h:i A")
											));
										 ?>
									</div>
								</div>
							</div>
						</div>
					   <div class="row-fluid">
					   <div class="span6">
								<div class="control-group p-complaint">
									<label class="control-label">Prisoner Number <?php echo MANDATORY; ?> :</label>
									<div class="controls">
										<?php echo $this->Form->input('prisoner_no',array('div'=>false,'label'=>false,'onChange'=>'getPrisonerStationInfo(this.value)','class'=>'form-control','type'=>'select','options'=>$prisonerList, 'empty'=>'-- Select Prisoner Number --','required','id'=>'prisoner_no','title'=>'Please provide prisoner number'));?>
									</div>
								</div>
							</div>
					   <div class="span6">
							<div class="control-group">
								<label class="control-label">Prisoner Name <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->text('prisoner_name',array('div'=>false,'label'=>false,'placeholder'=>'Prisoner Name','class'=>'form-control','readonly','id'=>'prisoner_name'));?>
								</div>
							</div>
						</div>
						</div>
						<div class="row-fluid">
						<div class="span6">
							<div class="control-group">
								<label class="control-label">Prison Station Id <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->text('prison_station_code',array('div'=>false,'label'=>false,'placeholder'=>'Prison Station Code','class'=>'form-control','readonly','id'=>'prison_station_code'));?>
								</div>
							</div>
						</div>
						<div class="span6">
							<div class="control-group">
								<label class="control-label">Name Of Station <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php
									 echo $this->Form->text('name_of_station',array('div'=>false,'label'=>false,'placeholder'=>'Name Of Station','type'=>'text','class'=>'form-control','readonly','id'=>'name_of_station'));?>
								</div>
							</div>
						</div>
						</div>
						
						<div class="row-fluid">
						<div class="span6">
							<div class="control-group p-complaint">
								<label class="control-label">Priority  :</label>
								<div class="controls">
									<?php echo $this->Form->input('priority',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$priorityList, 'empty'=>'-- Select Priority --','id'=>'priority','required','title'=>'Please provide priority'));?>
								</div>
							</div>
						</div>
						<div class="span6">
							<div class="control-group">
								<label class="control-label">Complaint <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->textarea('complaint',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Complaint','class'=>'form-control','required','title'=>'Please provide complaint description'));?>
								</div>
							</div>
						</div>
						</div>
						<div class="row-fluid">
							<div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Is Forward<?php echo $req; ?> :</label>
                                    <div class="controls uradioBtn">
                                        <?php 
                                            $is_dual_citizen = 0;
                                            if(isset($this->data['Prisonercomplaint']['is_complaint_forward']))
                                                $is_dual_citizen = $this->data['Prisonercomplaint']['is_complaint_forward'];
                                            $options2= array('0'=>'No','1'=>'Yes');
                                            $attributes2 = array(
                                                'legend' => false, 
                                                'value' => $is_dual_citizen,
                                                'onChange'=>'showDestination(this.value)',
                                            );
                                            echo $this->Form->radio('is_complaint_forward', $options2, $attributes2);
                                           ?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6 destination">
								<div class="control-group ">
									<label class="control-label">Destination  :</label>
									<div class="controls">
										<?php 
										$poiName=$funcall->getName(Configure::read('PRINCIPALOFFICER_USERTYPE'),'Usertype','name');
										$oiName=$funcall->getName(Configure::read('OFFICERINCHARGE_USERTYPE'),'Usertype','name');
										$destinationList=array(
											Configure::read('PRINCIPALOFFICER_USERTYPE')=>$poiName,
											Configure::read('OFFICERINCHARGE_USERTYPE')=>$oiName);
										echo $this->Form->input('forwarded_to',array('div'=>false,'label'=>false,'class'=>'form-control','type'=>'select','options'=>$destinationList, 'empty'=>'-- Select Destination --','id'=>'forwarded_to','required','title'=>'Please provide destination User'));?>
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
var selectedOption = $("input:radio[name='data[Prisonercomplaint][is_complaint_forward]']:checked").val();
if(selectedOption!=''){
  showDestination(selectedOption);
}

		var today = new Date();
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose:true,
            endDate: "today",
            maxDate: today
        }).on('changeDate', function (ev) {
                $(this).datepicker('hide');
            });

	if($('#prisoner_no').val() != '')
	{
		getPrisonerStationInfo($('#prisoner_no').val());
	}
});
function showDestination(isdual)
{
    if(isdual == 1)
    {
    	$('#forwarded_to').attr("required", "true");
        $('.destination').show();
    }
    else 
    {
    	$('#forwarded_to').removeAttr("required");
        $('.destination').hide();
    }
}
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

$(function(){
    $("#PrisonercomplaintAddForm").validate({
     
      ignore: "",
            rules: {  
                
            },
            messages: {
                

            },
               
    });
  });
</script>
