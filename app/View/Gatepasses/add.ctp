<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box">
				<div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
					<h5>Add New Court Detail </h5>
					<div style="float:right;padding-top: 7px;">
						<?php echo $this->Html->link(__('Courts  List'), array('action' => 'index'), array('escape'=>false,'class'=>'btn btn-success btn-mini')); ?>
						&nbsp;&nbsp;
					</div>
				</div>
				<div class="widget-content nopadding">
					<?php echo $this->Form->create('Court',array('class'=>'form-horizontal'));?>
					<?php echo $this->Form->input('id', array('type'=>'hidden'))?>
						<div class="row-fluid">
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Prisoner No<?php echo MANDATORY; ?> :</label>
									<div class="controls">
									   <?php
								echo $this->Form->input('prisoner_id',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'text',
								  
								  
								  
								  'required',
								));
							 ?>
									</div>
								</div>
							</div>     
							<div class="span6">
							<div class="control-group">
								<label class="control-label">Date of Discharge <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->input('date_of_discharge',array('div'=>false,'label'=>false,'placeholder'=>'Enter Court Code',
								  'data-date-format'=>"dd-mm-yyyy",
								  'readonly'=>'readonly','class'=>'form-control datepicker','required'));?>
								</div>
							</div>
						</div>
						</div>
					   <div class="row-fluid">
						<div class="span6">
							<div class="control-group">
									<label class="control-label">Discharge Type :</label>
									<div class="controls">
									  <?php echo $this->Form->input('dischargereasons_id',array('div'=>false,'label'=>false,'type'=>'select','empty'=>'-- Select Court Level --','placeholder'=>'Enter Region','class'=>'form-control','required'));?>
									</div>
								</div>
						</div>
						<div class="span6">
							<div class="control-group">
								<label class="control-label">Finger Print<?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->file('court_level_id',array('div'=>false,'label'=>false,'class'=>'form-control','required'));?>
								</div>
							</div>
						</div>
						</div>
						<div class="row-fluid">
						<div class="span6">
							<div class="control-group">
								<label class="control-label">Physical Address<?php echo MANDATORY; ?>  :</label>
								<div class="controls">
									<?php echo $this->Form->textarea('physical_address',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Physical Address','class'=>'form-control','required'));?>
								</div>
							</div>
						</div>
						 <div class="span6">
							<div class="control-group">
								<label class="control-label">Postal Address  :</label>
								<div class="controls">
									<?php echo $this->Form->textarea('postal_address',array('div'=>false,'label'=>false,'placeholder'=>'Enter Postal Address','class'=>'form-control','required'));?>
								</div>
							</div>
						</div>
						</div>
						<div class="row-fluid">
						<div class="span6">
							<div class="control-group">
								<label class="control-label">Region :</label>
								<div class="controls">
									<?php echo $this->Form->input('state_id',array('div'=>false,'label'=>false,'type'=>'select','options'=>$state,'empty'=>'-- Select Region --','placeholder'=>'Enter Region','class'=>'form-control','required'));?>
								</div>
							</div>
						</div>
						 <div class="span6">
							<div class="control-group">
								<label class="control-label">District :</label>
								<div class="controls">
									<?php echo $this->Form->input('district_id',array('div'=>false,'label'=>false,'placeholder'=>'Enter Response','options'=>$district,'empty'=>'-- Select District --','class'=>'form-control','required'));?>
								</div>
							</div>
						</div>
						</div>
						<div class="row-fluid">
							<div class="span6">
								<div class="control-group">
									<label class="control-label">GPS location :</label>
									<div class="controls">
									   <?php
								echo $this->Form->input('gps_location',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'text',
								  'readonly'=>'readonly',
								  
								  
								  'required',
								));
							 ?>
									</div>
								</div>
							</div>     
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Phone Number :</label>
									<div class="controls">
									   <?php
								echo $this->Form->input('phone_no',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'text',
								  'required',
								));
							 ?>
									</div>
								</div>
							</div>     
								
						</div>
						<div class="row-fluid">
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Fax Number :</label>
									<div class="controls">
									   <?php
								echo $this->Form->input('fax_no',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'text',
								  
								  
								  
								  'required',
								));
							 ?>
									</div>
								</div>
							</div>     
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Email Address :</label>
									<div class="controls">
									   <?php
								echo $this->Form->input('email_id',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'text',
								  'required',
								));
							 ?>
									</div>
								</div>
							</div>     
								
						</div>
						<div class="row-fluid">
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Magisterial Area<?php echo MANDATORY; ?> :</label>
									<div class="controls">
									   <?php
								echo $this->Form->input('magisterials_id',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'select',
								  'options'=>$magisterial_id, 'empty'=>'-- Select Magisterial Area --',
								  'required',
								));
							 ?>
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
<script src='https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyBodFycVkrwRnET3bCpvNZe2LdZkVRPdD0'></script>
<script type="text/javascript">
$(document).ready(function(){
	$('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
	
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
$(document).on('focusout', '#CourtPhysicalAddress', function(){
    var geocoder =  new google.maps.Geocoder();
    geocoder.geocode( { 'address': $('#CourtPhysicalAddress').val()}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            $('#CourtGpsLocation').val(results[0].geometry.location.lat() + "," +results[0].geometry.location.lng());
            //$('.push-down').text(); 
          } 
        });
});
</script>
