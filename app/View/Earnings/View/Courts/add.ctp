<?php
if(isset($this->data['Court']['date_of_opening']) && $this->data['Court']['date_of_opening']!=''){
	$this->request->data['Court']['date_of_opening'] = date("d-m-Y", strtotime($this->data['Court']['date_of_opening']));
}
if(isset($this->data['Court']['phone_no']) && $this->data['Court']['phone_no']!=''){
        $this->request->data['Court']['phone_no'] = explode(",", $this->data['Court']['phone_no']);
      }
if(isset($this->data['Court']['email_id']) && $this->data['Court']['email_id']!=''){
        $this->request->data['Court']['email_id'] = explode(",", $this->data['Court']['email_id']);
      }
?>
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
									<label class="control-label">Name of court<?php echo MANDATORY; ?> :</label>
									<div class="controls">
									   <?php
								echo $this->Form->input('name',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'text',
								  'placeholder'=>'Enter Name Of Court',								  
								  'data-date-format'=>"dd-mm-yyyy",	
								  'required',
								  'title'	=> "Please provide court name"
								));
							 ?>
									</div>
								</div>
							</div>     
							<div class="span6">
							<div class="control-group">
								<label class="control-label">Court Code <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->input('court_code',array('div'=>false,'label'=>false,'placeholder'=>'Enter Court Code','class'=>'form-control','required','title'=>"Please provide court code"));?>
								</div>
							</div>
						</div>
						</div>
					   <div class="row-fluid">
						<div class="span6">
							<div class="control-group">
									<label class="control-label">Date Of opening :</label>
									<div class="controls">
									   <?php
								echo $this->Form->input('date_of_opening',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'text',
								  'class'=>'datepicker',
								  'data-date-format'=>"dd-mm-yyyy",
								  'readonly'=>'readonly',
								  'required','title'=>"Please select opening date"
								));
							 ?>
									</div>
								</div>
						</div>
						<div class="span6">
							<div class="control-group">
								<label class="control-label">Level <?php echo MANDATORY; ?> :</label>
								<div class="controls">
									<?php echo $this->Form->input('courtlevel_id',array('div'=>false,'label'=>false,'type'=>'select','options'=>$court_level_id,'empty'=>'','class'=>'span11 pmis_select','required','title'=>"Please select court lebel"));?>
								</div>
							</div>
						</div>
						</div>
						<div class="row-fluid">
						<div class="span6">
							<div class="control-group">
								<label class="control-label">Physical Address<?php echo MANDATORY; ?>  :</label>
								<div class="controls">
									<?php echo $this->Form->textarea('physical_address',array('div'=>false,'label'=>false,'type'=>'text','placeholder'=>'Enter Physical Address','class'=>'form-control','required','title'=>"Please provide physical address"));?>
								</div>
							</div>
						</div>
						 <div class="span6">
							<div class="control-group">
								<label class="control-label">Postal Address  :</label>
								<div class="controls">
									<?php echo $this->Form->textarea('postal_address',array('div'=>false,'label'=>false,'placeholder'=>'Enter Postal Address','class'=>'form-control','required','title'=>"Please provide postal address"));?>
								</div>
							</div>
						</div>
						</div>
						<div class="row-fluid">
						<div class="span6">
							<div class="control-group">
								<label class="control-label">Region :</label>
								<div class="controls">
									<?php echo $this->Form->input('state_id',array('div'=>false,'label'=>false,'type'=>'select','options'=>$state,'empty'=>'','placeholder'=>'Enter Region','class'=>'span11 pmis_select','required','title'=>"Please select region"));?>
								</div>
							</div>
						</div>
						 <div class="span6">
							<div class="control-group">
								<label class="control-label">District :</label>
								<div class="controls">
									<?php echo $this->Form->input('district_id',array('div'=>false,'label'=>false,'placeholder'=>'Enter District','options'=>$district,'empty'=>'','class'=>'span11 pmis_select','required','title'=>"Please select district"));?>
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
										  'required'=>false,'title'=>"Please provide gps location"
										));
									 ?>
									</div>
								</div>
							</div>     
							<div class="span6">
								<?php
								if(isset($this->data['Court']['phone_no']) && is_array($this->data['Court']['phone_no']) && count($this->data['Court']['phone_no'])>0){
									foreach ($this->data['Court']['phone_no'] as $phone_nokey => $phone_novalue) {
										?>
										<div class="control-group">
											<label class="control-label">Phone Number <?php echo ++$phone_nokey; ?> :</label>
											<div class="controls">
											   <?php
													echo $this->Form->input('phone_no.',array(
													  'div'=>false,
													  'label'=>false,
													  'type'=>'text',
													  'required',
													  'class'=>'mobile',
													  'value'	=> $phone_novalue,
													  'id'	=> "phone_no".$phone_nokey
													));
												 ?>
											</div>
										</div>
										<?php
									}
								}else{
								?>
								<div class="control-group">
									<label class="control-label">Phone Number 1 :</label>
									<div class="controls">
									   <?php
								echo $this->Form->input('phone_no.',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'text',
								   'placeholder'=>'Enter Phone Number 1',
								  'required',
								  'id'	=> "phone_no1"
								));
							 ?>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Phone Number 2 :</label>
									<div class="controls">
									   <?php
								echo $this->Form->input('phone_no.',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'text',
								  'placeholder'=>'Enter Phone Number 2',
								  'required',
								  'id'	=> "phone_no2"
								));
							 ?>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Phone Number 3 :</label>
									<div class="controls">
									   <?php
								echo $this->Form->input('phone_no.',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'text',
								  'placeholder'=>'Enter Phone Number 3',
								  'required',								  
								  'id'	=> "phone_no3"
								));
							 ?>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Phone Number 4 :</label>
									<div class="controls">
									   <?php
								echo $this->Form->input('phone_no.',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'text',
								  'placeholder'=>'Enter Phone Number 4',
								  'required',
								  'id'	=> "phone_no4"
								));
							 ?>
									</div>
								</div>
								<?php
								}
								?>
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
								  'placeholder'=>'Enter The Fax Number',
								  'type'=>'text',
								  
								  
								  
								  'required',
								));
							 ?>
									</div>
								</div>
							</div>     
							<div class="span6">
								<?php
								if(isset($this->data['Court']['email_id']) && is_array($this->data['Court']['email_id']) && count($this->data['Court']['email_id'])>0){
									foreach ($this->data['Court']['email_id'] as $email_idkey => $email_idvalue) {
										?>
										<div class="control-group">
											<label class="control-label">Email Address<?php echo ++$email_idkey; ?> :</label>
											<div class="controls">
											   <?php
													echo $this->Form->input('email_id.',array(
													  'div'=>false,
													  'label'=>false,
													  'type'=>'text',
													  'required',
													  'value'	=> $email_idvalue,
													  'id'	=> "email_id".$email_idkey
													));
												 ?>
											</div>
										</div>
										<?php
									}
								}else{
								?>
								<div class="control-group">
									<label class="control-label">Email Address 1:</label>
									<div class="controls">
									   <?php
											echo $this->Form->input('email_id.',array(
											  'div'=>false,
											  'label'=>false,
											  'type'=>'text',
											  'placeholder'=>'Enter The Email Address 1',
											  'required',
											  'id'	=> 'email_id1'
											));
										 ?>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Email Address 2:</label>
									<div class="controls">
									   <?php
											echo $this->Form->input('email_id.',array(
											  'div'=>false,
											  'label'=>false,
											  'type'=>'text',
											  'placeholder'=>'Enter The Email Address 2',
											  'required',
											  'id'	=> 'email_id2'
											));
										 ?>
									</div>
								</div>
								<?php
								}
								?>
							</div>     
								
						</div>
						<div class="row-fluid">
							<div class="span6">
								<div class="control-group">
									<label class="control-label">Jurisdiction Area<?php echo MANDATORY; ?> :</label>
									<div class="controls">
									   <?php
								echo $this->Form->input('magisterial_id',array(
								  'div'=>false,
								  'label'=>false,
								  'type'=>'select',
								  'class'=>'span11 pmis_select',
								  'options'=>$magisterial_id, 'empty'=>'',
								  'required','title'=>"Please select magisterial area"
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
<script type="text/javascript">
$(function(){
    $("#CourtAddForm").validate({
     
      ignore: "",
            rules: {  
               // 'data[Court][name]': {
               //      required: true,
               //  },
               // 'data[Court][physical_address]': {
               //      required: true,
               //  },
               // 'data[Court][magisterials_id]': {
               //      required: true,
               //  },
               // 'data[Court][court_code]': {
               //      required: true,
               //  },
               // 'data[Court][court_level_id]': {
               //      required: true,
               //  },
               //  'data[Court][date_of_opening]': {
               //      required: false,
               //  },
               //  'data[Court][state_id]': {
               //      required: false,
               //  },
               //  'data[Court][gps_location]':{
               //  	required:false,
               //  },
               //  'data[Court][fax_no]':{
               //  	required:false,
               //  },
               //  'data[Court][postal_address]':{
               //  	required:false,
               //  },
               //  'data[Court][district_id]':{
               //  	required:false,
               //  },
               //  'data[Court][phone_no]':{
               //  	required:false,
               //  },
               //  'data[Court][email_id]':{
               //  	required:false,
               //  },
            },
            messages: {
                // 'data[Court][name]': {
                //     required: "This Field is Required.",
                // },
                // 'data[Court][physical_address]': {
                //     required: "This Field is Required.",
                // },
                // 'data[Court][magisterials_id]': {
                //     required: "This Field is Required.",
                // },
                // 'data[Court][court_code]': {
                //     required: "This Field is Required.",
                // },
                // 'data[Court][court_level_id]': {
                //     required: "This Field is Required.",
                // },
            },
               
    });
  });
</script>
<script src='https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyBodFycVkrwRnET3bCpvNZe2LdZkVRPdD0'></script>
<script type="text/javascript">
$(document).ready(function(){
	$('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
	
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
